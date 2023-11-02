#!/usr/bin/env bash
##
# Enable modules.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }

echo "[INFO] Enabling theme modules."

echo "  > Removing all files."
rm -Rf /app/web/sites/default/files/* >/dev/null || true

echo "  > Enable modules required by CivicTheme."
drush php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enable admin theme and set as default."
if [ "${DRUPAL_PROFILE}" = "govcms" ]; then
  # Enable stable9 theme and set as default theme.
  # This is required to remove other theme to avoid polluting configuration.
  drush theme-enable stable9
  drush config-set system.theme default stable9

  # Remove other themes.
  drush theme-uninstall claro || true
  drush theme-uninstall govcms_bartik || true
  drush theme-uninstall bartik || true
elif [ "${DRUPAL_PROFILE}" = "minimal" ]; then
  drush theme-enable claro
  drush config-set system.theme admin claro
  drush config-set node.settings use_admin_theme 1
fi

echo "  > Enable CivicTheme theme and set as default."
drush theme-enable civictheme
drush config-set system.theme default civictheme
drush config-set media.settings standalone_url true

drush theme-uninstall stable9 || true

echo "[ OK ] Finished enabling theme modules."
