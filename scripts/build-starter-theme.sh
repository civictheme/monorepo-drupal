#!/usr/bin/env bash
##
# Create a sub-theme.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# Drush alias.
DRUSH_ALIAS="${DRUSH_ALIAS:-}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "==> Create civic_demo sub-theme."

pushd $APP/docroot/themes/contrib/civic >/dev/null || exit 1
pwd
ls
php civic-create-subtheme.php civic_demo "Civic Demo" "Demo sub-theme for a Civic theme."
[ ! -d $APP/docroot/themes/custom/civic_demo ] && echo "ERROR: Failed to create civic_demo sub-theme." && exit 1

popd >/dev/null || exit 1
