#!/usr/bin/env bash
##
# Run lint checks.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

BUILD_DIR="${BUILD_DIR:-build}"

echo
echo "-------------------------------"
echo " Fixing theme code             "
echo "-------------------------------"
echo

echo "  > Running Drupal Rector fixer."
"${BUILD_DIR}"/vendor/bin/rector --clear-cache

echo "  > Running PHPCS fixer."
"${BUILD_DIR}"/vendor/bin/phpcbf

