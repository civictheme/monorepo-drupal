#!/usr/bin/env bash
# Toggle / set SCAFFOLD_PROVISION_TYPE in .env (profile|database).
#
# Local-only: only edits the host .env. Does NOT affect Salsa Hosting deploys —
# those use the value baked into the cloud environment.
#
# Usage:
#   ahoy configure:provision              # toggle current value
#   ahoy configure:provision profile      # set to profile
#   ahoy configure:provision database     # set to database
#   ahoy configure:provision --show       # print current value
#   ahoy configure:provision -h|--help    # this help

set -euo pipefail

KEY="SCAFFOLD_PROVISION_TYPE"
ENV_FILE="${ENV_FILE:-.env}"
ENV_EXAMPLE="${ENV_EXAMPLE:-.env.example}"

log()  { printf '\033[0;36m[configure]\033[0m %s\n' "$*"; }
warn() { printf '\033[0;33m[configure]\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[0;31m[configure]\033[0m %s\n' "$*" >&2; exit 2; }

usage() {
  sed -n '2,12p' "$0" | sed 's/^# \{0,1\}//'
}

# Match `KEY=value`, tolerant of optional `export `, surrounding whitespace,
# and single/double/no quoting. Captures the bare value in $2.
KEY_RE="^[[:space:]]*(export[[:space:]]+)?${KEY}[[:space:]]*=[[:space:]]*[\"']?([^\"'#[:space:]]*)[\"']?[[:space:]]*(#.*)?$"

current_value() {
  [ -f "$ENV_FILE" ] || return 0
  while IFS= read -r line; do
    if [[ "$line" =~ $KEY_RE ]]; then
      printf '%s\n' "${BASH_REMATCH[2]}"
      return 0
    fi
  done < "$ENV_FILE"
}

ensure_env_file() {
  if [ -f "$ENV_FILE" ]; then
    return 0
  fi
  if [ -f "$ENV_EXAMPLE" ]; then
    cp "$ENV_EXAMPLE" "$ENV_FILE"
    log "Seeded $ENV_FILE from $ENV_EXAMPLE"
  else
    : > "$ENV_FILE"
    log "Created empty $ENV_FILE"
  fi
}

set_value() {
  local new="$1"
  ensure_env_file

  local tmp
  tmp="$(mktemp "${ENV_FILE}.XXXXXX")"
  local replaced=0
  if [ -s "$ENV_FILE" ]; then
    while IFS= read -r line || [ -n "$line" ]; do
      if [[ $replaced -eq 0 && "$line" =~ $KEY_RE ]]; then
        printf '%s=%s\n' "$KEY" "$new" >> "$tmp"
        replaced=1
      else
        printf '%s\n' "$line" >> "$tmp"
      fi
    done < "$ENV_FILE"
  fi
  if [ $replaced -eq 0 ]; then
    printf '%s=%s\n' "$KEY" "$new" >> "$tmp"
  fi

  # Preserve mode of the original file when possible.
  if [ -f "$ENV_FILE" ]; then
    chmod --reference="$ENV_FILE" "$tmp" 2>/dev/null || true
  fi
  mv "$tmp" "$ENV_FILE"
}

validate() {
  case "$1" in
    profile|database) return 0 ;;
    *) die "Invalid value '$1'. Expected: profile | database" ;;
  esac
}

main() {
  case "${1-}" in
    -h|--help)
      usage
      exit 0
      ;;
    --show)
      cur="$(current_value)"
      if [ -z "${cur:-}" ]; then
        log "$KEY is not set in $ENV_FILE"
      else
        log "$KEY=$cur"
      fi
      exit 0
      ;;
    "")
      # Toggle.
      cur="$(current_value)"
      case "${cur:-}" in
        profile)  new=database ;;
        database) new=profile ;;
        "")       new=profile ;;
        *)        die "Current $KEY='$cur' is not 'profile' or 'database' — refusing to toggle. Set explicitly with 'ahoy configure:provision profile|database'." ;;
      esac
      set_value "$new"
      log "$KEY: ${cur:-<unset>} -> $new"
      log "Next: run 'ahoy provision' to apply."
      ;;
    profile|database)
      validate "$1"
      cur="$(current_value)"
      if [ "${cur:-}" = "$1" ]; then
        log "$KEY already $1 — no change."
        exit 0
      fi
      set_value "$1"
      log "$KEY: ${cur:-<unset>} -> $1"
      log "Next: run 'ahoy provision' to apply."
      ;;
    *)
      die "Unknown argument '$1'. Try 'ahoy configure:provision --help'."
      ;;
  esac
}

main "$@"
