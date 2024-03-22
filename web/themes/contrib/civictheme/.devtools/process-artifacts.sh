#!/usr/bin/env bash
##
# Process test artifacts.
#
# This runs only in CI.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

if [ -d "$(pwd)/build/web/sites/simpletest/browser_output" ]; then
  echo "==> Copying Simpletest test artifacts"
  mkdir -p /tmp/artifacts/simpletest
  cp -Rf "$(pwd)/build/web/sites/simpletest/browser_output/." /tmp/artifacts/simpletest
fi
