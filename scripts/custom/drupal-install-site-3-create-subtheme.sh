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

php civic-create-subtheme.php civic_demo "Civic Demo" "Demo sub-theme for a Civic theme."
[ ! -d $APP/docroot/themes/contrib/civic_demo ] && echo "ERROR: Failed to create civic_demo sub-theme." && exit 1

popd >/dev/null || exit 1

echo "  > Installing civic_demo theme."
$drush ${DRUSH_ALIAS} theme:enable civic_demo -y

echo "  > Making civic_demo a default theme."
$drush ${DRUSH_ALIAS} config-set system.theme default civic_demo -y

echo "  > Updating civic_demo theme settings."
$drush ${DRUSH_ALIAS} ev "module_load_include('inc', 'cd_core', 'cd_core.civic_demo'); cd_core_civic_demo_update_theme_settings();"

if [ "$SKIP_SUBTHEME_FE" != "1" ] && command -v npm &> /dev/null; then
  pushd $APP/docroot/themes/contrib/civic_demo >/dev/null || exit 1

  echo "  > Installing FE dependencies."
  npm ci

  echo "  > Running FE build."
  npm run build

  popd >/dev/null || exit 1
fi
