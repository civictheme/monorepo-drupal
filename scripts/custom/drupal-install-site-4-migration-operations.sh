#!/usr/bin/env bash
##
# Migration operations that will run after website is installed.
#

set -eu
[ -n "${DREVOPS_DEBUG:-}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# Flag to completely skip migrations.
MIGRATION_SKIP="${MIGRATION_SKIP:-0}"

# Limit of migration items.
MIGRATION_IMPORT_LIMIT="${MIGRATION_IMPORT_LIMIT:-all}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "==> Started migration post site install operations."
environment="$($drush ev "print \Drupal\core\Site\Settings::get('environment');")"
echo "    Environment:     ${environment}"
echo "    Migration skip:  ${MIGRATION_SKIP}"
echo "    Migration limit: ${MIGRATION_IMPORT_LIMIT}"
echo

if [ "${MIGRATION_SKIP}" = "1" ]; then
  echo "==> Skipping Migration post site install operations."
  return
fi

if [ -n "${DREVOPS_DRUPAL_INSTALL_USE_MAINTENANCE_MODE}" ]; then
  echo "  > Disabling maintenance mode before running core group migrations."
  $drush state:set system.maintenance_mode 1 --input-format=integer -y
fi

echo "==> Running migrations."

# @todo Add setting remote URL for migrations.

# @todo Remove 'echo' once CivicTheme 1.5 is released.
echo $drush migrate:reset civictheme_migrate -y
echo $drush migrate:import --group=civictheme_migrate --update -y

if [ -n "${DREVOPS_DRUPAL_INSTALL_USE_MAINTENANCE_MODE}" ]; then
  echo "  > Disabling maintenance mode before running core group migrations."
  $drush state:set system.maintenance_mode 0 --input-format=integer -y
fi

echo "==> Finished migration post site install operations."
