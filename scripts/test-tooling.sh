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
