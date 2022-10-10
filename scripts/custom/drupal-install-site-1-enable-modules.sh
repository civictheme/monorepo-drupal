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
$drush php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enable admin theme and set as default."
if [ "${DREVOPS_DRUPAL_PROFILE}" = "govcms" ]; then
  $drush -y theme-enable adminimal_theme
  $drush -y config-set system.theme admin adminimal_theme
  $drush -y theme-uninstall claro || true
  $drush -y theme-uninstall govcms_bartik || true
  $drush -y theme-uninstall bartik || true
elif [ "${DREVOPS_DRUPAL_PROFILE}" = "minimal" ]; then
  $drush -y theme-enable claro
  $drush -y config-set system.theme admin claro
  $drush -y config-set node.settings use_admin_theme 1
fi

echo "  > Enable CivicTheme theme and set as default."
$drush -y theme-enable civictheme
$drush -y config-set system.theme default civictheme
$drush -y config-set media.settings standalone_url true
