#!/bin/bash
# Toggle DRUPAL_DEBUG-time PHP overrides at container start.
#
# Mechanism (mirrors 01-xdebug.sh):
#   - Build time: 95-php-debug.ini ships with the assertion directives
#     (`zend.assertions=1`, `assert.exception=1`) prefixed by `;` (commented).
#   - Runtime: when DRUPAL_DEBUG=1, this script uncomments them so Drupal core's
#     assert() component checks fire. zend.assertions can't be raised from a
#     `-1` baseline via ini_set() at runtime (PHP silently warns), so we flip it
#     at the conf.d layer here, before PHP boots.
#   - When DRUPAL_DEBUG != 1, do nothing — the shipped file is already off.
set -euo pipefail

PHP_DEBUG_INI="/usr/local/etc/php/conf.d/95-php-debug.ini"

if [ "${DRUPAL_DEBUG:-0}" != "1" ]; then
  # Off (default). Nothing to do — the shipped ini is already commented out.
  exit 0
fi

if [ ! -f "$PHP_DEBUG_INI" ]; then
  echo "ERROR: $PHP_DEBUG_INI missing — image built without the php-debug ini?" >&2
  exit 1
fi

# Uncomment the assertion directives. Guard on the commented zend.assertions
# line so a re-run on an already-enabled file is a no-op (idempotent).
sed -i \
  -e 's/^;zend\.assertions=1/zend.assertions=1/' \
  -e 's/^;assert\.exception=1/assert.exception=1/' \
  "$PHP_DEBUG_INI"

echo "DRUPAL_DEBUG PHP overrides enabled (zend.assertions=1, assert.exception=1)"
