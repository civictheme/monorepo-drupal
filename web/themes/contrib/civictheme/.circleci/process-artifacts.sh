#!/usr/bin/env bash
##
# Process test artifacts.
#
# This runs only in CircleCI.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Directory where Drupal site will be built.
BUILD_DIR="${BUILD_DIR:-build}"

#-------------------------------------------------------------------------------

if [ -d "$(pwd)/${BUILD_DIR}/web/sites/simpletest/browser_output" ]; then
  echo "==> Copying Simpletest test artifacts"
  mkdir -p /tmp/artifacts/simpletest
  cp -Rf "$(pwd)/${BUILD_DIR}/web/sites/simpletest/browser_output/." /tmp/artifacts/simpletest
fi
