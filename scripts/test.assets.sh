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

echo "  > Test theme assets."
bats tests/bats/assets.basetheme.bats --tap
