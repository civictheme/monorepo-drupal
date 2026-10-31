#!/bin/bash
# Post-deploy: runs on every Salsa Hosting container start, in every environment.
#
# Salsa Hosting builds code in GitHub Actions and publishes an image; this
# script decides what a container start means. The platform offers no
# deploy-vs-restart signal — idle-sleep wake-ups, task replacements and fresh
# deploys all run the same entrypoint with the same variables — so the image
# carries a BUILD_ID (written by the Dockerfile, unique per CI run) and the
# last provisioned id is recorded in Drupal state:
#
#   id matches         restart of an already-provisioned image (idle-sleep
#                      wake, scaling, crash recovery): run deploy.sh only
#                      (updb + config:import + cr — non-destructive).
#   id differs/absent  first start of a new image (= a deploy), or Drupal is
#                      not installed at all: reinstall the site from scratch
#                      via provision.sh. The CivicTheme demo sites reinstall
#                      from profile on every deploy BY DESIGN, production
#                      included — there is no content to preserve.
#
# The fresh install runs in the BACKGROUND. A full provision (minimal profile
# + CivicTheme + generated content) takes minutes; the platform kills container
# starts that outrun the startup grace period (default ~120s), and a failing
# entrypoint hook blocks the container — the environment would replace the
# task and loop without ever becoming reachable. Backgrounding lets Apache
# start immediately (the site serves errors until the install lands) and the
# provision logs to the container log via /proc/1/fd/1.
set -euo pipefail

CYAN='\033[0;36m'
NC='\033[0m'
log() { printf "${CYAN}[post-deploy]${NC} %s\n" "$*"; }

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
DEPLOY_SCRIPT="/opt/drupal/scripts/quant/deploy.sh"
PROVISION_SCRIPT="/opt/drupal/scripts/quant/provision.sh"
BUILD_ID_FILE="/opt/drupal/.build-id"
# On the app-private volume: shared across tasks and not web-accessible.
LOCK_FILE="/opt/drupal/private/.provision.lock"
STATE_KEY="scaffold.provisioned_build_id"

log "environment: ${QUANT_ENV_TYPE:-<unset>}"

for script in "$DEPLOY_SCRIPT" "$PROVISION_SCRIPT"; do
  if [ ! -x "$script" ]; then
    printf "[post-deploy] ERROR: not found or not executable: %s\n" "$script" >&2
    exit 1
  fi
done

image_build_id="$(cat "$BUILD_ID_FILE" 2>/dev/null || echo unknown)"

provisioned_build_id=""
if "$DRUSH" status --field=bootstrap 2>/dev/null | grep -qi 'success'; then
  provisioned_build_id="$("$DRUSH" state:get "$STATE_KEY" 2>/dev/null || true)"
fi

if [ -n "$provisioned_build_id" ] && [ "$provisioned_build_id" = "$image_build_id" ]; then
  log "Image ${image_build_id} already provisioned — running deploy steps."
  exec "$DEPLOY_SCRIPT"
fi

log "New image ${image_build_id} (provisioned: ${provisioned_build_id:-<none>}) — fresh install, backgrounded."

# Server installs are always profile/recipe installs — database mode reads a
# dump from /import, a local-only bind mount. The defaults mirror .env.example;
# platform variables win when set.
export SCAFFOLD_PROVISION_TYPE=profile
export DRUPAL_INSTALL_PROFILE="${DRUPAL_INSTALL_PROFILE:-minimal}"
export DRUPAL_SITE_NAME="${DRUPAL_SITE_NAME:-CivicTheme Source}"
export DRUPAL_SITE_EMAIL="${DRUPAL_SITE_EMAIL:-p.civictheme@salsa.digital}"

# Normally the app-private volume mount; make the lock location exist even
# without it so the provision never dies on the lock fd.
mkdir -p "$(dirname "$LOCK_FILE")"

(
  # The db service may still be coming up on a brand-new environment.
  tries=0
  until "$DRUSH" sql:query 'SELECT 1' >/dev/null 2>&1; do
    tries=$((tries + 1))
    if [ "$tries" -ge 60 ]; then
      echo "[post-deploy] ERROR: database unreachable after 5 minutes — aborting provision." >&2
      exit 1
    fi
    sleep 5
  done

  # Serialise across concurrently starting tasks; the winner provisions, the
  # loser re-checks the recorded id and skips instead of reinstalling again.
  flock -w 3600 9 || { echo "[post-deploy] ERROR: provision lock timeout." >&2; exit 1; }
  current="$("$DRUSH" state:get "$STATE_KEY" 2>/dev/null || true)"
  if [ -n "$current" ] && [ "$current" = "$image_build_id" ]; then
    echo "[post-deploy] Another task already provisioned ${image_build_id} — skipping."
    exit 0
  fi

  "$PROVISION_SCRIPT"

  # Entrypoint hooks run before Apache; anything the install wrote onto the
  # shared files volume must stay writable by Apache (www-data).
  if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data /opt/drupal/web/sites/default/files /opt/drupal/private || true
  fi

  "$DRUSH" state:set "$STATE_KEY" "$image_build_id" -y
  echo "[post-deploy] Fresh install of ${image_build_id} complete."
) 9>"$LOCK_FILE" >>/proc/1/fd/1 2>&1 &

log "Provision running (PID $!) — Apache starts now; the site serves once the install lands."
exit 0
