#!/usr/bin/env bash
#
# Publish the current stack's cli + DB images to the local Docker registry.
#
# DB snapshot approach:
#   1. drush sql:dump on the running stack (Drupal-aware, consistent snapshot).
#   2. Spin up a side container from a "seeder" mariadb image
#      (default drevops/mariadb-drupal-data). Key property: this image's real
#      datadir is /home/db-data (the /var/lib/mysql VOLUME declaration is
#      vestigial — mysql doesn't use it). So we bind-mount a host dir at
#      /home/db-data and mysql writes the full data directory there.
#   3. Restore the dump via mysql client.
#   4. Clean-shutdown mysql.
#   5. Build a new image FROM the seeder that simply `COPY`s the populated
#      host dir into /home/db-data. Because that path has NO VOLUME decl,
#      Docker doesn't discard the write — data lands as a proper image layer.
#   6. Push.
#
#   At spinup time, pulling this image and running a container from it
#   produces a mariadb whose datadir already contains the data — no SQL
#   import, no file-copy entrypoint needed.
#
# Usage:
#   ahoy image-publish              # tag = current git branch name
#   ahoy image-publish <tag>        # explicit tag (e.g. "develop", "v2.x")
#
# Env:
#   DREVOPS_IMAGE_REGISTRY        registry host (default: registry.docker.amazee.io)
#   DREVOPS_IMAGE_NAMESPACE       required — project namespace under the registry
#   DREVOPS_DB_DOCKER_IMAGE_BASE  seeder image for the side container
#                                 (default: drevops/mariadb-drupal-data:latest)
#   COMPOSE_PROJECT_NAME          the local cli image tag (provided by ahoy env loading)

set -euo pipefail

REGISTRY="${DREVOPS_IMAGE_REGISTRY:-registry.docker.amazee.io}"
NAMESPACE="${DREVOPS_IMAGE_NAMESPACE:-}"
SEED_IMAGE="${DREVOPS_DB_DOCKER_IMAGE_BASE:-drevops/mariadb-drupal-data:latest}"
PROJECT="${COMPOSE_PROJECT_NAME:-${PWD##*/}}"

TAG="${1:-}"
if [ -z "$TAG" ]; then
  TAG="$(git rev-parse --abbrev-ref HEAD)"
fi
TAG="$(printf '%s' "$TAG" | sed -E 's#[^a-zA-Z0-9._-]+#-#g; s#^[-.]+##; s#[-.]+$##')"

[ -n "$NAMESPACE" ] || { echo "error: DREVOPS_IMAGE_NAMESPACE is not set (add to .env)" >&2; exit 1; }
[ -n "$TAG" ]       || { echo "error: empty tag after sanitization" >&2; exit 1; }

echo "[publish] registry:     $REGISTRY"
echo "[publish] namespace:    $NAMESPACE"
echo "[publish] project:      $PROJECT"
echo "[publish] tag:          $TAG"
echo "[publish] seeder image: $SEED_IMAGE"
echo

echo "[publish] verifying registry is reachable at http://${REGISTRY}/v2/ ..."
if ! curl -sf "http://${REGISTRY}/v2/" >/dev/null; then
  echo "error: registry ${REGISTRY} is not reachable." >&2
  echo "       start it with: (cd ~/work/consulting/apps/local-docker-registry && docker compose up -d)" >&2
  exit 1
fi

echo "[publish] verifying stack is running..."
if [ -z "$(docker compose ps -q cli 2>/dev/null)" ]; then
  echo "error: cli container is not running — run 'ahoy up' first" >&2
  exit 1
fi

echo "[publish] verifying site bootstrap (drush status)..."
if docker compose exec -T cli drush status --field=bootstrap 2>/dev/null | grep -q "Successful"; then
  echo "[publish] drush bootstrap OK"
else
  echo "[publish] WARNING: drush reports site is not bootstrapped — continuing anyway" >&2
fi
echo

# ---------------------------------------------------------------------------
# CLI image: tag the running cli image and push.
# ---------------------------------------------------------------------------

CLI_DST="${REGISTRY}/${NAMESPACE}/cli:${TAG}"

CLI_SRC=""
cli_cid="$(docker compose ps -q cli 2>/dev/null | head -n 1)"
if [ -n "$cli_cid" ]; then
  CLI_SRC="$(docker inspect --format '{{.Config.Image}}' "$cli_cid" 2>/dev/null || true)"
fi
if [ -z "$CLI_SRC" ]; then
  CLI_SRC="${PROJECT}:latest"
fi
echo "[publish] cli image: $CLI_SRC"

if ! docker image inspect "$CLI_SRC" >/dev/null 2>&1; then
  echo "error: local cli image '$CLI_SRC' not found — run 'ahoy up' (or 'ahoy build') first" >&2
  exit 1
fi

echo "[publish] tagging cli image: $CLI_SRC -> $CLI_DST"
docker tag "$CLI_SRC" "$CLI_DST"

echo "[publish] pushing $CLI_DST"
docker push "$CLI_DST"
echo

# ---------------------------------------------------------------------------
# DB image: dump running DB, restore into a seeder side container, commit, push.
# ---------------------------------------------------------------------------

DB_IMAGE_NAME="${NAMESPACE}/db:${TAG}"
DB_DST="${REGISTRY}/${DB_IMAGE_NAME}"

DUMP_REL=".data/image-publish-${TAG}.sql.gz"
DUMP_HOST="${PWD}/${DUMP_REL}"
SIDE_NAME="image-publish-seed-${NAMESPACE//[^a-zA-Z0-9_.-]/_}-${TAG//[^a-zA-Z0-9_.-]/_}-$$"
BUILD_CTX="$(mktemp -d)"

cleanup() {
  # Best-effort: remove the dump file, build context, and any lingering
  # side container.
  rm -f "$DUMP_HOST" 2>/dev/null || true
  [ -d "$BUILD_CTX" ] && rm -rf "$BUILD_CTX" 2>/dev/null || true
  if docker inspect "$SIDE_NAME" >/dev/null 2>&1; then
    docker rm -f "$SIDE_NAME" >/dev/null 2>&1 || true
  fi
}
trap cleanup EXIT

mkdir -p "$(dirname "$DUMP_HOST")"
rm -f "$DUMP_HOST"

echo "[publish] dumping DB via 'drush sql:dump --gzip' → $DUMP_REL (this can take a minute)"
docker compose exec -T cli bash -c "mkdir -p /app/$(dirname "$DUMP_REL") && drush sql:dump --result-file=/app/${DUMP_REL%.gz} --gzip --extra-dump='--no-tablespaces --skip-ssl'"
if [ ! -s "$DUMP_HOST" ]; then
  echo "error: SQL dump is empty or missing at $DUMP_HOST" >&2
  exit 1
fi
echo "[publish] dump size: $(du -h "$DUMP_HOST" | cut -f1)"

echo "[publish] pulling seeder image $SEED_IMAGE"
docker pull "$SEED_IMAGE"

# Bind-mount a host dir at /home/db-data — the seeder's ACTUAL datadir (the
# /var/lib/mysql VOLUME is vestigial, mysql doesn't touch it). Writing
# directly to a host path side-steps "docker commit skips volume contents"
# and "COPY under a VOLUME gets discarded" in one move.
SEED_DATA_DIR="$BUILD_CTX/mysql-data"
SEED_DATA_PATH_IN_CONTAINER="/home/db-data"
mkdir -p "$SEED_DATA_DIR"
chmod 0777 "$SEED_DATA_DIR"

echo "[publish] starting seeder side container: $SIDE_NAME (datadir bind-mounted to host)"
docker run -d --name "$SIDE_NAME" \
  -v "${SEED_DATA_DIR}:${SEED_DATA_PATH_IN_CONTAINER}" \
  -e MYSQL_DATABASE=drupal \
  -e MYSQL_USER=drupal \
  -e MYSQL_PASSWORD=drupal \
  "$SEED_IMAGE" >/dev/null

echo "[publish] waiting for seeder mysql to accept connections..."
ready=0
for _ in $(seq 1 60); do
  if docker exec "$SIDE_NAME" sh -c 'mysql -u drupal -pdrupal drupal -e "SELECT 1" >/dev/null 2>&1'; then
    ready=1; break
  fi
  sleep 2
done
if [ "$ready" -ne 1 ]; then
  echo "error: seeder mysql did not become ready within 120s" >&2
  docker logs --tail 40 "$SIDE_NAME" >&2 || true
  exit 1
fi
echo "[publish] seeder mysql is ready"

echo "[publish] restoring dump into seeder (this can take a few minutes for big DBs)"
# Rewrite MySQL-8-only collations on the fly so a MySQL-8 source dump can
# restore into the MariaDB seeder. utf8mb4_0900_* collations are MySQL-8+
# and unknown to MariaDB (ERROR 1273). utf8mb4_unicode_520_ci is the closest
# MariaDB-supported equivalent; utf8mb4_bin matches the MySQL binary variant.
# Local dev DB snapshot — byte-identical collation semantics are not required.
gunzip -c "$DUMP_HOST" \
  | sed -E 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_520_ci/g; s/utf8mb4_0900_as_cs/utf8mb4_unicode_520_ci/g; s/utf8mb4_0900_bin/utf8mb4_bin/g' \
  | docker exec -i "$SIDE_NAME" mysql -u drupal -pdrupal drupal

echo "[publish] clean-shutting down seeder mysql so data is flushed to disk"
# SIGTERM triggers mysqld's graceful shutdown path. Generous timeout for large DBs.
docker stop -t 180 "$SIDE_NAME" >/dev/null

# Fix permissions on the seed data so:
#   (a) docker build can read the host dir (files are owned by the seeder's
#       internal mysql UID, not the host user);
#   (b) whatever UID the consumer container runs mariadb as (DrevOps convention
#       is user: '1000' via x-user, but the image's own default USER is mysql
#       = UID 100 in the seeder) can read+write the files.
# Make everything owned by UID 1000 (the DrevOps user) AND mode a+rwX as a
# belt-and-braces so unconventional compose setups also work. Local dev DB
# image — permissive perms are fine.
echo "[publish] normalizing perms on seed dir (uid=1000, mode=a+rwX) so any compose USER can read/write"
docker run --rm -v "${SEED_DATA_DIR}:/data" alpine sh -c 'chown -R 1000:1000 /data && chmod -R a+rwX /data'

echo "[publish] seeded data dir size: $(du -sh "$SEED_DATA_DIR" | cut -f1)"
if [ "$(du -sk "$SEED_DATA_DIR" | cut -f1)" -lt 100 ]; then
  echo "error: seed data dir is suspiciously small — restore likely failed" >&2
  echo "  (contents below)" >&2
  ls -la "$SEED_DATA_DIR" >&2 || true
  exit 1
fi

cat > "$BUILD_CTX/Dockerfile" <<EOF
FROM ${SEED_IMAGE}
USER root
# --chown=1000:1000 matches the DrevOps compose convention (x-user: '1000').
# The preceding chmod -R a+rwX on the host dir ensures other UIDs can still
# read/write, belt-and-braces for non-DrevOps compose setups.
COPY --chown=1000:1000 mysql-data ${SEED_DATA_PATH_IN_CONTAINER}
USER mysql
EOF

echo "[publish] building $DB_DST (FROM $SEED_IMAGE, data baked into ${SEED_DATA_PATH_IN_CONTAINER})"
docker build -t "$DB_DST" "$BUILD_CTX"

echo "[publish] pushing $DB_DST"
docker push "$DB_DST"
echo

echo "[publish] DONE. Images pushed:"
echo "  $CLI_DST"
echo "  $DB_DST"
echo
echo "View: http://drevops-registry.docker.amazee.io"
