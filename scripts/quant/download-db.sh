#!/usr/bin/env bash
# Pull a database backup from Salsa Hosting via the qc CLI and stash it as a
# timestamped dump under .data/. Does NOT overwrite .data/db.sql — use
# `ahoy mysql:select-db-dump <name>` to activate.
#
# Output filename: db_salsa_<env>_<YYYYMMDD_HHMMSS>.sql
#
# Requires the qc CLI on the host: `npm install -g @quantcdn/quant-cloud-cli`.
# Authenticate once with `qc auth login`.
set -euo pipefail

DEST_DIR="${DEST_DIR:-./.data}"
QC_BIN="${QC_BIN:-qc}"
# Set QUANT_APP_NAME in .env; configure.sh writes it at create time. No default
# — there is no sensible one, and the guard below turns an unset value into a
# clear error. Runtime override: `QUANT_APP_NAME=foo ahoy download-db`.
APP="${QUANT_APP_NAME:-}"
ENVIRONMENT="${QUANT_BACKUP_ENV:-production}"
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
DEST_FILENAME="${DEST_FILENAME:-db_salsa_${ENVIRONMENT}_${TIMESTAMP}.sql}"
DEST_FILE="${DEST_DIR}/${DEST_FILENAME}"

log()  { printf '\033[0;36m[download-db]\033[0m %s\n' "$*"; }
die()  { printf '\033[0;31m[download-db]\033[0m %s\n' "$*" >&2; exit 2; }

# Defensive: never call qc with a non-name. Empty means configure.sh hasn't run
# or QUANT_APP_NAME is missing from .env; the {{…}} arm catches a value
# hand-copied from a pre-rebuild template. Either way there is no such app on
# Salsa Hosting and the request would be nonsense.
#
# NOTE: deliberately does NOT test for the scaffold's placeholder name. This
# file must contain no sentinel literal — configure.sh rewrites the sentinel
# tree-wide, which would turn such a test into "reject the user's real app
# name" and break every valid invocation.
case "$APP" in
  '' | *'{{'*'}}'*)
    die "QUANT_APP_NAME is not set (raw='$APP'). Set it in .env or pass it on the command line."
    ;;
esac

command -v "$QC_BIN" >/dev/null 2>&1 \
  || die "qc CLI not found. Install: npm install -g @quantcdn/quant-cloud-cli && qc auth login"

mkdir -p "$DEST_DIR"

log "Downloading ${ENVIRONMENT} backup for app '${APP}' → ${DEST_DIR}"
"$QC_BIN" backup download --app "$APP" --environment "$ENVIRONMENT" --output "$DEST_DIR"

# qc writes either a .sql.gz or a .sql; extract/copy into the timestamped name.
shopt -s nullglob
gz_files=( "${DEST_DIR}"/*.sql.gz )
sql_files=( "${DEST_DIR}"/*.sql )
shopt -u nullglob

# Filter out any pre-existing db_salsa_*.sql (older downloads) when picking the
# newest "raw" file qc just produced.
filter_qc_output() {
  for f in "$@"; do
    case "$(basename "$f")" in
      db_salsa_*|db.sql) continue ;;
    esac
    printf '%s\n' "$f"
  done
}

mapfile -t fresh_gz  < <(filter_qc_output "${gz_files[@]}"  | xargs -r ls -t 2>/dev/null)
mapfile -t fresh_sql < <(filter_qc_output "${sql_files[@]}" | xargs -r ls -t 2>/dev/null)

if [ "${#fresh_gz[@]}" -gt 0 ]; then
  src="${fresh_gz[0]}"
  log "Extracting $(basename "$src") → $(basename "$DEST_FILE")"
  gunzip -kfc "$src" > "$DEST_FILE"
elif [ "${#fresh_sql[@]}" -gt 0 ]; then
  src="${fresh_sql[0]}"
  log "Copying $(basename "$src") → $(basename "$DEST_FILE")"
  cp "$src" "$DEST_FILE"
else
  die "No .sql or .sql.gz files found in ${DEST_DIR} after qc backup download"
fi

log "Saved ${DEST_FILE}"
log "Activate with: ahoy mysql:select-db-dump $(basename "$DEST_FILE")"

# Final stdout line = the resolved path, for programmatic consumption.
printf '%s\n' "$DEST_FILE"
