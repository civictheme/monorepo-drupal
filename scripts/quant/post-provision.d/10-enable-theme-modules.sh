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
# Installs the modules the theme's optional config (block placements) depends
# on; the recipes below install everything the content model needs.
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
fi

echo "  > Enable CivicTheme theme and set as default."
# The theme must be installed before the recipes apply: the entity display
# config they ship uses layout plugins the theme provides.
drush theme-enable civictheme
drush config-set system.theme default civictheme

echo "  > Apply the CivicTheme content model recipes."
# Plugin discovery can race the module installs a recipe performs (observed
# as 'The "search_api_query" plugin does not exist' while the search recipe
# imports its view, with the module installed moments earlier). Recipes are
# idempotent, so rebuild caches and retry once before giving up.
# The container's 256M PHP limit is also marginal for the content-model
# recipe (observed as an OOM in PdoTrait during the govcms config import),
# so the apply gets its own headroom.
apply_recipe() {
  if ! php -d memory_limit=512M "$DRUSH" -y recipe:apply "$1"; then
    echo "  > Recipe $1 failed; rebuilding caches and retrying once."
    drush cache:rebuild
    php -d memory_limit=512M "$DRUSH" -y recipe:apply "$1"
  fi
}
theme_dir="${APP_ROOT}/web/themes/contrib/civictheme"
apply_recipe "${theme_dir}/recipes/civictheme_site"
apply_recipe "${theme_dir}/recipes/civictheme_search"

echo "  > Install optional config whose dependencies the recipes satisfied."
# The theme's optional block placements depend on config the recipes created
# (menus, block content types, views); re-run the optional config sweep so
# they are picked up deterministically.
drush php:eval "\Drupal::service('config.installer')->installOptionalConfig();"

if [ "${DRUPAL_INSTALL_PROFILE:-}" = "minimal" ]; then
  # Needs the node module, which the recipes installed.
  drush config-set node.settings use_admin_theme 1
fi

drush theme-uninstall stable9 || true

echo "[ OK ] Finished enabling theme modules."
