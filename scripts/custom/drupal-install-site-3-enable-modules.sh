#!/usr/bin/env bash
##
# Enable other modules.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

if [ "${DREVOPS_DRUPAL_PROFILE}" = "govcms" ]; then
  echo "  > Remove GovCMS configs."
  $drush -y pm-enable civictheme_govcms
  $drush civictheme_govcms:remove-config
else
  echo "  > Enable Admin module."
  $drush -y pm-enable civictheme_admin
fi

echo "  > Enable development module."
$drush -y pm-enable civictheme_dev
