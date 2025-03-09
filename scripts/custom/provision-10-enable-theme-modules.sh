#!/usr/bin/env bash
##
# Enable modules.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }


echo "  > Removing all site files."
rm -Rf /app/web/sites/default/files/* >/dev/null || true

if [[ "$DRUPAL_PROFILE" != recipes/* ]] || [[ "$DRUPAL_PROFILE" != "recipes/civictheme_site_install" ]]; then
echo "[INFO] Enabling theme modules."
  if [ "${DRUPAL_PROFILE}" = "govcms" ]; then
    echo "  > Enable admin theme and set as default."
    # Enable stable9 theme and set as default theme.
    # This is required to remove other theme to avoid polluting configuration.
    drush theme-enable stable9
    drush config-set system.theme default stable9

    # Remove other themes.
    drush theme-uninstall govcms_bartik || true
    drush theme-uninstall bartik || true
  elif [ "${DRUPAL_PROFILE}" = "minimal" ]; then
    drush theme-enable claro
    drush config-set system.theme admin claro
    drush config-set node.settings use_admin_theme 1
  fi

  if [[ "$DRUPAL_PROFILE" != recipes/* ]] || [[ "$DRUPAL_PROFILE" != "recipes/civictheme_site_install" ]]; then
    echo "  > Enable CivicTheme theme and set as default."
    drush theme-enable civictheme
    drush config-set system.theme default civictheme
    drush config-set media.settings standalone_url true

    drush theme-uninstall stable9 || true
  fi
  echo "[ OK ] Finished enabling theme modules."
fi
