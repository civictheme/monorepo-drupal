#!/usr/bin/env bash
##
# Enable CivicTheme and the modules it requires.
#
# Post-provision hook — executed by scripts/quant/run-post-provision.sh with
# DRUSH, APP_ROOT, DRUPAL_INSTALL_PROFILE, SCAFFOLD_PROVISION_TYPE and
# QUANT_ENV_TYPE exported.
#
# Hooks run after EVERY deploy (including the per-start entrypoint deploy on a
# server), not just after a fresh provision. The file wipe below is only valid
# right after `drush site:install`, so this hook no-ops unless provision.sh
# set the fresh-provision marker.
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"

drush() { "$DRUSH" -y "$@"; }

if [ "${SCAFFOLD_FRESH_PROVISION:-}" != "1" ]; then
  echo "[ OK ] Not a fresh provision - skipping theme module setup."
  exit 0
fi

echo "[INFO] Enabling theme modules."

echo "  > Removing all files."
rm -Rf "${APP_ROOT}"/web/sites/default/files/* >/dev/null 2>&1 || true

echo "  > Enable modules required by CivicTheme."
drush php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enable admin theme and set as default."
if [ "${DRUPAL_INSTALL_PROFILE:-}" = "govcms" ]; then
  # Enable stable9 theme and set as default theme.
  # This is required to remove other theme to avoid polluting configuration.
  drush theme-enable stable9
  drush config-set system.theme default stable9
elif [ "${DRUPAL_INSTALL_PROFILE:-}" = "minimal" ]; then
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
