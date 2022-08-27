#!/usr/bin/env bash
##
# Enable modules.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "==> Removing all files."
rm -Rf "${APP}"/docroot/sites/default/files/* >/dev/null || true

echo "  > Enable modules required by CivicTheme."
$drush ev "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enable admin theme and set as default."
$drush -y then adminimal_theme
$drush -y config-set system.theme admin adminimal_theme

echo "  > Enable CivicTheme theme and set as default."
$drush -y then civictheme || true
$drush -y then civictheme || true
$drush -y config-set system.theme default civictheme
$drush -y config-set media.settings standalone_url true
