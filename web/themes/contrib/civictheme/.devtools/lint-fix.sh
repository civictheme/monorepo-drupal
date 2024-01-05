#!/usr/bin/env bash
##
# Run lint checks.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

pushd "build" >/dev/null || exit 1

echo "  > Running Drupal Rector fixer."
vendor/bin/rector process --debug

echo "  > Running PHPCS fixer."
vendor/bin/phpcbf

popd >/dev/null || exit 1
