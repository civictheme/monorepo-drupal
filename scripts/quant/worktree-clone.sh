#!/usr/bin/env bash
#
# Clone a running scaffold site into another compose project: the MySQL data
# volume, and the built app image.
#
# ~1s for a 300MB database. No dump, no SQL import, no registry.
#
# Usage:
#   ahoy worktree:clone <dest-project> [--force]     # source = this project
#   scripts/quant/worktree-clone.sh <source-project> <dest-project> [--force]
#
#   ./worktree-clone.sh mysite mysite--feature-x     # main → worktree
#   ./worktree-clone.sh mysite snapshot              # main → holding copy
#   ./worktree-clone.sh snapshot mysite--feature-y   # holding → worktree
#
# <project> is the Docker Compose project name: COMPOSE_PROJECT_NAME from that
# directory's .env, defaulting to the directory basename. This script never
# reads a project directory — it works purely on Docker objects named after it:
#   volume  <project>_db_data
#   image   <project>-app:latest
#
# Run BEFORE the destination's first `docker compose up`. A Compose volume is
# only adoptable while empty; once MySQL has initialised its own, re-seeding
# does nothing (this refuses rather than corrupt it — --force replaces).
#
# The source database is stopped for the duration of the copy and restarted
# afterwards, including on Ctrl-C. Copying a live InnoDB data directory yields a
# torn snapshot, so this is not optional.
#
# Tip: to spin up many worktrees, clone main → a holding project once (paying
# the stop), then clone the holding project → each worktree. The holding volume
# is never mounted by a running server, so no further stops are needed.
#
# Full worktree workflow: docs/worktrees.md.

set -euo pipefail

VOLUME_SUFFIX="${DB_VOLUME_SUFFIX:-db_data}"
APP_SERVICE="${APP_SERVICE:-app}"
COPY_IMAGE="${COPY_IMAGE:-alpine}"
STOP_TIMEOUT="${STOP_TIMEOUT:-180}"

FORCE=0
ARGS=()
for a in "$@"; do
  case "$a" in
    --force) FORCE=1 ;;
    -h|--help) sed -n '2,34p' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
    -*) echo "error: unknown option: $a" >&2; exit 2 ;;
    *) ARGS+=("$a") ;;
  esac
done

if [ "${#ARGS[@]}" -ne 2 ]; then
  echo "usage: $(basename "$0") <source-project> <dest-project> [--force]" >&2
  echo "try:   $(basename "$0") --help" >&2
  exit 2
fi

SRC_PROJECT="${ARGS[0]}"
DST_PROJECT="${ARGS[1]}"
SRC_VOL="${SRC_PROJECT}_${VOLUME_SUFFIX}"
DST_VOL="${DST_PROJECT}_${VOLUME_SUFFIX}"
SRC_IMG="${SRC_PROJECT}-${APP_SERVICE}:latest"
DST_IMG="${DST_PROJECT}-${APP_SERVICE}:latest"

log() { printf '\033[0;36m[clone]\033[0m %s\n' "$*"; }
die() { printf '\033[0;31m[clone]\033[0m %s\n' "$*" >&2; exit 1; }

[ "$SRC_VOL" != "$DST_VOL" ] || die "source and destination are the same project"

# --- checks ----------------------------------------------------------------

docker volume inspect "$SRC_VOL" >/dev/null 2>&1 || die "no such volume: $SRC_VOL
       Check the project name. Databases on this machine:
$(docker volume ls --format '{{.Name}}' | grep "_${VOLUME_SUFFIX}\$" | sed 's/^/         /' || echo '         (none)')"

if docker volume inspect "$DST_VOL" >/dev/null 2>&1; then
  # A non-empty destination means that stack has already initialised its own
  # database. Copying over it merges two data directories — unrecoverable.
  if [ -n "$(docker run --rm -v "$DST_VOL":/d "$COPY_IMAGE" ls -A /d 2>/dev/null | head -n 1)" ]; then
    [ "$FORCE" -eq 1 ] || die "$DST_VOL already exists and has data.
       The destination stack has initialised its own database. Tear it down:
         cd <dest-dir> && ahoy down:force
       then re-run, or pass --force to replace it."
    log "--force: removing existing $DST_VOL"
    docker volume rm "$DST_VOL" >/dev/null
  fi
fi

# --- stop the source database ----------------------------------------------

running="$(docker ps -q --filter "volume=${SRC_VOL}" || true)"
STOPPED=""

restart_source() {
  if [ -n "$STOPPED" ]; then
    log "restarting source database"
    # shellcheck disable=SC2086
    docker start $STOPPED >/dev/null 2>&1 \
      || printf '\033[0;31m[clone]\033[0m could not restart: %s\n' "$STOPPED" >&2
    STOPPED=""
  fi
}
trap restart_source EXIT HUP INT TERM

if [ -n "$running" ]; then
  log "stopping source database for a consistent copy"
  # shellcheck disable=SC2086
  docker stop -t "$STOP_TIMEOUT" $running >/dev/null
  STOPPED="$running"
fi

# --- copy the database -----------------------------------------------------

# The compose labels make `docker compose up` adopt this volume silently;
# without them it warns "volume already exists but was not created by Compose".
log "creating $DST_VOL"
docker volume create \
  --label com.docker.compose.project="$DST_PROJECT" \
  --label com.docker.compose.volume="$VOLUME_SUFFIX" \
  --label com.docker.compose.version="$(docker compose version --short 2>/dev/null || echo unknown)" \
  "$DST_VOL" >/dev/null

log "copying database $SRC_VOL → $DST_VOL"
started="$(date +%s)"
# `cp -a` runs as root inside the container and preserves ownership, so the
# mysql-owned datadir copies faithfully with no chown pass. Trailing /. copies
# the directory contents, dotfiles included.
docker run --rm -v "$SRC_VOL":/src:ro -v "$DST_VOL":/dst "$COPY_IMAGE" cp -a /src/. /dst/
elapsed="$(( $(date +%s) - started ))"

size="$(docker run --rm -v "$DST_VOL":/d "$COPY_IMAGE" du -sh /d 2>/dev/null | cut -f1 || echo '?')"
log "copied ${size} in ${elapsed}s"

restart_source
trap - EXIT HUP INT TERM

# --- reuse the app image ---------------------------------------------------

# Compose names built images <project>-<service>:latest and image names are
# local to this daemon, so tagging is enough for the worktree's `up` to skip
# the build entirely.
if docker image inspect "$SRC_IMG" >/dev/null 2>&1; then
  docker tag "$SRC_IMG" "$DST_IMG"
  log "tagged app image $SRC_IMG → $DST_IMG (worktree will not rebuild)"
else
  log "note: no image $SRC_IMG — the worktree will build its app image on first up"
fi

cat <<EOF

[clone] DONE. In the destination worktree directory:

  1. set the project name in its .env — this one line moves its containers,
     its database volume and its URL together:
       SCAFFOLD_PROJECT=$DST_PROJECT
  2. ahoy up                  # MySQL boots from the copied data, no import
  3. ahoy composer install    # the worktree's src/ has no vendor/ yet
  4. ahoy drush deploy        # config import + db updates, if the branch needs it

  Do NOT run 'ahoy build' in the destination — it re-provisions, which drops
  every table and destroys the database this just copied.
EOF
