#!/usr/bin/env bash
##
# Run lint checks.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

BUILD_DIR="${BUILD_DIR:-build}"

echo
echo "-------------------------------"
echo " Linting theme code            "
echo "-------------------------------"
echo

echo "  > Running PHPCS."
"${BUILD_DIR}"/vendor/bin/phpcs

echo "  > Running TWIGCS."
"${BUILD_DIR}"/vendor/bin/twigcs

echo "  > Running phpstan."
"${BUILD_DIR}"/vendor/bin/phpstan

echo "  > Running Drupal Rector."
"${BUILD_DIR}"/vendor/bin/rector --dry-run --clear-cache

echo "  > Running PHPMD."
"${BUILD_DIR}"/vendor/bin/phpmd . text phpmd.xml
