#!/usr/bin/env bash
# Activate one of the dumps under .data/db_*.sql by hard-linking it to
# .data/db.sql (the working file consumed by provision.sh). Source dumps are
# NEVER deleted — only the active link is replaced.
#
# Why a hard link instead of `cp`: dumps can be 6GB+, so copying every
# activation is wasteful. A hard link is O(1), and detection of the active
# dump becomes an inode equality test (`[ src -ef active ]`) instead of a
# `cmp -s` content scan. mysql/drush import is read-only on the file, so
# sharing the inode is safe. We also chmod 0444 the inode after linking to
# turn any accidental write-through (e.g. `cp foo .data/db.sql`) into a
# clean EACCES instead of silently corrupting the source dump.
#
# Usage:
#   select-db-dump.sh                       # interactive picker (TTY)
#   select-db-dump.sh <name|path>           # non-interactive
#   select-db-dump.sh --latest              # pick newest .data/db_*.sql
#
# Bare names are resolved under $DATA_DIR (.data by default). Absolute paths
# and relative paths to existing files are used as-is.
set -euo pipefail

DATA_DIR="${DATA_DIR:-./.data}"
ACTIVE="${DATA_DIR}/db.sql"

log()  { printf '\033[0;36m[select-db-dump]\033[0m %s\n' "$*"; }
die()  { printf '\033[0;31m[select-db-dump]\033[0m %s\n' "$*" >&2; exit 2; }

resolve_arg() {
  local arg="$1"
  if [ -f "$arg" ]; then
    printf '%s\n' "$arg"; return 0
  fi
  if [ -f "${DATA_DIR}/$arg" ]; then
    printf '%s\n' "${DATA_DIR}/$arg"; return 0
  fi
  return 1
}

activate() {
  local src="$1"
  [ -f "$src" ] || die "Not a file: $src"
  # GNU `ln -f` refuses to link a file to itself, so handle the no-op case
  # explicitly — keeps re-running --latest or re-selecting the current dump
  # from erroring out under `set -e`.
  if [ -e "$ACTIVE" ] && [ "$src" -ef "$ACTIVE" ]; then
    log "Already active: $(basename "$src")"
    return 0
  fi
  # Both paths live under $DATA_DIR, so the same-filesystem requirement for
  # hard links is guaranteed. `-f` replaces any existing $ACTIVE entry.
  ln -f "$src" "$ACTIVE"
  chmod 0444 "$ACTIVE"
  log "Active dump → $(basename "$ACTIVE")  (source: $src)"
}

shopt -s nullglob
candidates=( "${DATA_DIR}"/db_*.sql )
shopt -u nullglob

case "${1:-}" in
  --latest)
    [ "${#candidates[@]}" -gt 0 ] || die "No db_*.sql files in ${DATA_DIR}"
    newest="$(ls -t "${candidates[@]}" | head -1)"
    activate "$newest"
    ;;

  '')
    if [ ! -t 0 ]; then
      die "Non-interactive (stdin not a TTY) — pass a filename or --latest. Examples:
  ahoy mysql:select-db-dump db_salsa_production_20260301_120000.sql
  ahoy mysql:select-db-dump --latest"
    fi
    [ "${#candidates[@]}" -gt 0 ] || die "No db_*.sql files in ${DATA_DIR} — run \`ahoy download-db\` first."
    log "Available dumps in ${DATA_DIR} (newest first):"
    mapfile -t sorted < <(ls -1t "${candidates[@]}")
    i=1
    for f in "${sorted[@]}"; do
      size="$(ls -lh "$f" | awk '{print $5}')"
      mtime="$(ls -l "$f" | awk '{print $6,$7,$8}')"
      marker=""
      # `-ef` is a same-inode test: O(1), no content reads — relies on the
      # hard link created by activate().
      if [ -f "$ACTIVE" ] && [ "$f" -ef "$ACTIVE" ]; then
        marker="  ← active"
      fi
      printf '  %2d) %-50s  %6s  %s%s\n' "$i" "$(basename "$f")" "$size" "$mtime" "$marker"
      i=$((i+1))
    done
    read -r -p "Select [1-${#sorted[@]}]: " choice
    [[ "$choice" =~ ^[0-9]+$ ]] || die "Invalid selection: $choice"
    [ "$choice" -ge 1 ] && [ "$choice" -le "${#sorted[@]}" ] || die "Out of range: $choice"
    activate "${sorted[$((choice - 1))]}"
    ;;

  *)
    resolved="$(resolve_arg "$1")" || die "Not found: $1 (and not under ${DATA_DIR}/)"
    activate "$resolved"
    ;;
esac
