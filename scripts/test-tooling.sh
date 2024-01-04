#!/usr/bin/env bash
##
# Run tooling tests.
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

[ ! -d "tests/bats/node_modules" ] && npm --prefix tests/bats ci

bats() { "tests/bats/node_modules/.bin/bats" "$@"; }

echo "  > Test theme assets."
bats tests/bats/assets.bats --tap

if [ "${CIVICTHEME_SUBTHEME_ACTIVATION_SKIP}" != "1" ]; then
  if [ "${CIVICTHEME_INSTALL_SIBLING}" = "1" ]; then
    echo "  > Test Sub-theme sibling assets."
    bats tests/bats/assets_demo_sibling.bats --tap
  else
    echo "  > Test Sub-theme custom assets."
    bats tests/bats/assets_demo_custom.bats --tap
  fi
fi
