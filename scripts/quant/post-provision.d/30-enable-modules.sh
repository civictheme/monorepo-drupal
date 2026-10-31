#!/usr/bin/env bash
##
# Enable additional modules (admin/dev helpers, or GovCMS config cleanup).
#
# Post-provision hook. Idempotent — safe on every deploy.
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"

drush() { "$DRUSH" -y "$@"; }

echo "[INFO] Enabling additional modules."

if [ "${CIVICTHEME_ADDITIONAL_MODULES_ACTIVATION_SKIP:-}" = "1" ]; then
  echo "[ OK ] Skipping additional modules activation."
  exit 0
fi

if [ "${DRUPAL_INSTALL_PROFILE:-}" = "govcms" ]; then
  echo "  > Remove GovCMS configs."
  drush pm-enable civictheme_govcms
  drush civictheme_govcms:remove-config
else
  echo "  > Enable Admin module."
  drush pm-enable civictheme_admin
  drush pm-enable admin_toolbar admin_toolbar_search
fi

echo "  > Enable development module."
drush pm-enable civictheme_dev

echo "  > Clear drupal cache."
drush cr

echo "[ OK ] Finished enabling additional modules."
