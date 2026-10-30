#!/usr/bin/env bash
# Audit the scaffold for legacy env-var names that the four-tier rename
# (see docs/variables.md) should have eliminated. Greps the repo for each
# old name as a whole word and reports any hits outside the allow-list.
# Exits 0 when clean, 1 on any leak. Run with --list to see the rename
# table this script enforces.
#
# Usage:
#   scripts/check-env-naming.sh              # audit
#   scripts/check-env-naming.sh --list       # print rename table
#
# Bash 4+ required (associative arrays).

set -euo pipefail

if [[ "${BASH_VERSINFO[0]:-0}" -lt 4 ]]; then
  echo "error: bash 4+ required for associative arrays (have $BASH_VERSION)" >&2
  exit 2
fi

# Rename table — keys are legacy names, values are the canonical
# replacement. This is the source of truth for the audit.
#
# A row earns its place by having a live target. QUANT_SCAFFOLD_ORG and
# QUANT_SCAFFOLD_REPO were dropped because SCAFFOLD_ORG and SCAFFOLD_REPO exist
# nowhere in the tree in either form — they belonged to the retired
# `ahoy update-scaffold`, and the audit was policing a rename that had no
# destination. SCAFFOLD_VERSION stays: configure.sh sets it.
declare -A RENAMES=(
  [PROJECT_NAME]=SCAFFOLD_PROJECT
  [QUANT_PROVISION_TYPE]=SCAFFOLD_PROVISION_TYPE
  [QUANT_POST_PROVISION_ENABLED]=SCAFFOLD_POST_PROVISION_ENABLED
  [QUANT_POST_PROVISION_ON_ERROR]=SCAFFOLD_POST_PROVISION_ON_ERROR
  [QUANT_SCAFFOLD_VERSION]=SCAFFOLD_VERSION
  [QUANT_INSTALL_PROFILE]=DRUPAL_INSTALL_PROFILE
  [USE_LOCAL_MAILHOG]=SCAFFOLD_MAILHOG_ENABLED
  [XDEBUG_ENABLE]=SCAFFOLD_XDEBUG_ENABLED
  [REDIS_ENABLED]=DRUPAL_REDIS_ENABLED
  [REDIS_HOST]=DRUPAL_REDIS_HOST
  [REDIS_PORT]=DRUPAL_REDIS_PORT
  [REDIS_PREFIX]=DRUPAL_REDIS_PREFIX
  [STAGE_FILE_PROXY_URL]=DRUPAL_STAGE_FILE_PROXY_ORIGIN
  # DISABLE_DB_TLS → DB_TLS_ENABLED with sense inverted (=true → =0).
  [DISABLE_DB_TLS]=DB_TLS_ENABLED
  # Dropped re-export aliases that provision.sh / run-post-provision.sh
  # used to set. Hooks now read SCAFFOLD_PROVISION_TYPE and
  # DRUPAL_INSTALL_PROFILE directly.
  [PROVISION_TYPE]=SCAFFOLD_PROVISION_TYPE
  [PROFILE]=DRUPAL_INSTALL_PROFILE
)

# Per-name overrides. Default behaviour: grep -rEn "\bOLD\b" across the
# whole repo (minus common excludes), no post-filter.
#
# CUSTOM_PATTERN[old]    — override the ERE used to match.
# SCAN_PATHS[old]        — restrict the search to these paths (default '.').
# FALSE_POSITIVE_RE[old] — drop lines matching this ERE (post-filter).
declare -A CUSTOM_PATTERN=(
  # PROFILE is a common substring (PHP_IDE_CONFIG, XDEBUG_PROFILE, etc.).
  # We care only about it appearing as a bash assignment LHS in the
  # provisioning scripts, which is where the legacy alias used to live.
  [PROFILE]='(^|[^A-Za-z0-9_])PROFILE='
)
declare -A SCAN_PATHS=(
  [PROFILE]='scripts/quant'
)
declare -A FALSE_POSITIVE_RE=(
  # The old PROJECT_NAME allowance covered the installer's mustache token and
  # its substitution-map key. Neither survives the de-templating.
  # What remains is project-name-check.sh's error message, which names the
  # legacy variable on purpose so it can tell a developer what to rename.
  [PROJECT_NAME]='pre-rename PROJECT_NAME='
)

# Repo root = parent of scripts/
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

# Files that legitimately mention the legacy names (the migration record
# itself, this script). Anything in these paths is ignored unconditionally.
COMMON_EXCLUDES=(
  --exclude-dir=.git
  --exclude-dir=vendor
  --exclude-dir=node_modules
  --exclude-dir=.idea
  # Local-only agent scratch space (git-ignored).
  --exclude-dir=.claude-scratchpad
  # Legacy CircleCI pipeline — replaced by GitHub Actions in the scaffold
  # conversion Phase 3; drops out of the tree with it.
  --exclude-dir=.circleci
  # Downloaded SQL dumps — huge, and full of arbitrary column names.
  --exclude-dir=.data
  # Composer-managed code. The audit exists to catch legacy names in code this
  # project owns; Drupal core and contrib follow their own conventions and are
  # not ours to rename. drupal/redis — in this repo's own `require` — reads
  # getenv('REDIS_HOST') in its test suite, which the rename table flags, so
  # without these the audit fails on every project that has run
  # `composer install`. `bin` is the vendor binary dir (config.bin-dir = bin).
  --exclude-dir=core
  --exclude-dir=contrib
  --exclude-dir=libraries
  --exclude-dir=bin
  --exclude='variables.md'
  --exclude="$(basename "$0")"
)

usage() {
  sed -n '2,12p' "$0" | sed 's/^# \{0,1\}//'
}

list_renames() {
  printf '%-32s → %s\n' "OLD NAME" "NEW NAME"
  printf '%-32s   %s\n' "--------" "--------"
  for old in "${!RENAMES[@]}"; do
    printf '%-32s → %s\n' "$old" "${RENAMES[$old]}"
  done | sort
}

case "${1:-}" in
  -h|--help) usage; exit 0 ;;
  --list)    list_renames; exit 0 ;;
  '') ;;
  *) echo "error: unknown argument: $1" >&2; usage >&2; exit 2 ;;
esac

cd "$REPO_ROOT"

fail=0
total_hits=0
# Sort for deterministic output ordering across bash versions.
mapfile -t SORTED_OLD < <(printf '%s\n' "${!RENAMES[@]}" | sort)

for old in "${SORTED_OLD[@]}"; do
  new="${RENAMES[$old]}"
  pattern="${CUSTOM_PATTERN[$old]:-\\b${old}\\b}"
  scan_path="${SCAN_PATHS[$old]:-.}"
  fp="${FALSE_POSITIVE_RE[$old]:-}"

  hits="$(grep -rEn "$pattern" "${COMMON_EXCLUDES[@]}" "$scan_path" 2>/dev/null || true)"
  if [[ -n "$fp" ]]; then
    hits="$(printf '%s\n' "$hits" | grep -Ev "$fp" || true)"
  fi

  if [[ -n "$hits" ]]; then
    count="$(printf '%s\n' "$hits" | wc -l)"
    total_hits=$((total_hits + count))
    fail=1
    echo "✗ ${old}  →  rename to ${new}  (${count} occurrence$([[ $count -eq 1 ]] || echo s))"
    printf '%s\n' "$hits" | sed 's/^/    /'
    echo
  fi
done

if [[ $fail -eq 0 ]]; then
  echo "✓ env-var naming audit clean — no legacy names found."
  exit 0
fi

echo "✗ env-var naming audit found ${total_hits} legacy reference$([[ $total_hits -eq 1 ]] || echo s)."
echo "  Run \`$(basename "$0") --list\` to see the old → new rename table."
exit 1
