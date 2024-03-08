#!/usr/bin/env bash
##
# Enable other modules.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }

echo "[INFO] Enabling additional modules."
[ "${CIVICTHEME_ADDITIONAL_MODULES_ACTIVATION_SKIP:-}" = "1" ] && echo "[ OK ] Skipping additional modules activation" && return

if [ "${DRUPAL_PROFILE:-}" = "govcms" ]; then
  echo "  > Remove GovCMS configs."
  drush pm-enable civictheme_govcms
  drush civictheme_govcms:remove-config
else
  echo "  > Enable Admin module."
  drush pm-enable civictheme_admin
fi

echo "  > Enable development module."
drush pm-enable civictheme_dev

echo "[ OK ] Finished enabling additional modules."
