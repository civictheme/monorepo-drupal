#!/usr/bin/env bash
#
# Naming-contract guard for the civictheme_starter starterkit conversion.
#
# Core's `generate-theme --starterkit` blind-renames every occurrence of the
# kit machine name's case patterns across the generated tree. Any token that
# extends the machine name (for example `civictheme_starter_kit`) would be
# corrupted in generated output (`<target>_kit`), so such tokens must never
# exist in the repository.
#
# - FAILS on any tracked occurrence of `civictheme_starter_kit`.
# - REPORTS (without failing) every token matching `civictheme_starter[a-z0-9_]`
#   so new tokens extending the machine name get a human look. PascalCase hook
#   class prefixes (CivicThemeStarter*) are intended and do not match.
#
# Run from anywhere; the script anchors itself to the repository root.

set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."

# This script is the only place allowed to spell the forbidden token.
ALLOWLIST=(
  ':!scripts/lint.naming-contract.sh'
)

failed=0

echo "==> Checking tracked files for the forbidden token 'civictheme_starter_kit'"
if git grep -n -I 'civictheme_starter_kit' -- . "${ALLOWLIST[@]}"; then
  echo "ERROR: 'civictheme_starter_kit' extends the starterkit machine name and would be corrupted by theme generation. Remove or rename the occurrences above." >&2
  failed=1
else
  echo "OK: no occurrences."
fi

echo
echo "==> Tokens extending the machine name 'civictheme_starter' (report only)"
if git grep -n -I -E 'civictheme_starter[a-z0-9_]' -- . "${ALLOWLIST[@]}"; then
  echo "NOTE: snake-case extensions of the machine name are almost certainly bugs; review the occurrences above."
else
  echo "OK: no occurrences."
fi

exit "${failed}"
