#!/usr/bin/env bash
##
# Run lint checks.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

pushd "build" >/dev/null || exit 1

echo "  > Running PHPCS."
vendor/bin/phpcs

echo "  > Running PHPMD."
vendor/bin/phpmd . text phpmd.xml

echo "  > Running TWIGCS."
vendor/bin/twigcs

echo "  > Running phpstan."
vendor/bin/phpstan

echo "  > Running Drupal Rector."
vendor/bin/rector --dry-run --debug

popd >/dev/null || exit 1
