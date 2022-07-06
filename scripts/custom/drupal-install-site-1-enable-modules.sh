#!/usr/bin/env bash
##
# Enable modules.
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

echo "==> Removing all files."
rm -Rf "${APP}"/docroot/sites/default/files/* > /dev/null || true

echo "  > Enable modules required by CivicTheme."
$drush ${DRUSH_ALIAS} ev "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/civictheme.provision.inc'; civictheme_enable_modules();"

echo "  > Enable admin theme and set as default."
$drush ${DRUSH_ALIAS} -y then adminimal_theme
$drush ${DRUSH_ALIAS} -y config-set system.theme admin adminimal_theme

echo "  > Enable CivicTheme theme and set as default."
$drush ${DRUSH_ALIAS} -y then civictheme
$drush ${DRUSH_ALIAS} -y config-set system.theme default civictheme
$drush ${DRUSH_ALIAS} -y config-set media.settings standalone_url true

echo "  > Uninstall obsolete themes."
$drush ${DRUSH_ALIAS} -y thun claro
$drush ${DRUSH_ALIAS} -y thun govcms_bartik
$drush ${DRUSH_ALIAS} -y thun bartik

echo "  > Remove GovCMS configs."
$drush ${DRUSH_ALIAS} -y pm-enable civictheme_govcms
$drush ${DRUSH_ALIAS} civictheme_govcms:remove-config
