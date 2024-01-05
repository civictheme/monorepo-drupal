#!/usr/bin/env bash
##
# Run lint checks.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

echo "  > Running Drupal Rector fixer."
build/vendor/bin/rector process --debug

echo "  > Running PHPCS fixer."
build/vendor/bin/phpcbf

