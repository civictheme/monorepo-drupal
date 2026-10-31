#!/usr/bin/env bash
##
# Run tooling tests.
#

set -eu
[ -n "${SCAFFOLD_DEBUG:-}" ] && set -x

# The Drupal tree (web/, tests/) lives under src/ per the scaffold layout.
cd "$(dirname "$0")/../src"

[ ! -d "tests/bats/node_modules" ] && npm --prefix tests/bats ci

bats() { "tests/bats/node_modules/.bin/bats" "$@"; }

echo "  > Test base theme assets."
bats tests/bats/assets.basetheme.bats --tap

if [ "${CIVICTHEME_SUBTHEME_ACTIVATION_SKIP:-0}" != "1" ]; then
  if [ "${CIVICTHEME_INSTALL_SIBLING:-0}" = "1" ]; then
    echo "  > Test sub-theme sibling assets."
    bats tests/bats/assets.subtheme.sibling.bats --tap
  else
    echo "  > Test sub-theme custom assets."
    bats tests/bats/assets.subtheme.custom.bats --tap
  fi
fi
