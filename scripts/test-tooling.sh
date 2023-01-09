#!/usr/bin/env bash
##
# Run tooling tests.
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

echo "==> Test BATS helpers."
bats tests/bats/helpers.bats --tap

echo "==> Test BATS mock."
bats tests/bats/mock.bats --tap

echo "==> Test Assets mock."
bats tests/bats/assets.bats --tap

if [ "${CIVICTHEME_SKIP_LIBRARY_INSTALL}" != "1" ]; then
  echo "  > Test Library assets."
  bats tests/bats/assets_library.bats --tap
fi

echo "  > Test theme assets."
bats tests/bats/assets.bats --tap

if [ "${CIVICTHEME_SKIP_SUBTHEME_ACTIVATION}" != "1" ]; then
  if [ "${CIVICTHEME_INSTALL_SIBLING}" = "1" ]; then
    echo "  > Test Sub-theme sibling assets."
    bats tests/bats/assets_demo_sibling.bats --tap
  else
    echo "  > Test Sub-theme custom assets."
    bats tests/bats/assets_demo_custom.bats --tap
  fi
fi
