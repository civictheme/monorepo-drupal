#!/usr/bin/env bash
##
# Optionally install and activate a sub-theme.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }

echo "[INFO] Activating sub-theme."
[ "${CIVICTHEME_SUBTHEME_ACTIVATION_SKIP:-}" = "1" ] && echo "[ OK ] Skipping sub-theme activation" && return

if [ ! -d /app/web/themes/custom/civictheme_demo ]; then
  echo "  > Creating civictheme_demo sub-theme."
  pushd /app/web/themes/contrib/civictheme >/dev/null || exit 1
  php civictheme_create_subtheme.php civictheme_demo "CivicTheme Demo" "Demo sub-theme for a CivicTheme theme."
  [ ! -d /app/web/themes/custom/civictheme_demo ] --remove-examples && echo "[ERROR] Failed to create civictheme_demo sub-theme." && exit 1
  popd >/dev/null || exit 1
fi

echo "  > Installing civictheme_demo theme."
drush theme:enable civictheme_demo

echo "  > Setting civictheme_demo as a default theme."
drush config-set system.theme default civictheme_demo

if [ "${CIVICTHEME_SUBTHEME_FE_BUILD_SKIP:-}" != "1" ] && command -v npm &> /dev/null; then
  pushd /app/web/themes/custom/civictheme_demo >/dev/null || exit 1

  if [ ! -d /app/web/themes/custom/civictheme_demo/node_modules ]; then
    echo "  > Installing FE dependencies."
    npm install
  fi

  echo "  > Building FE assets."
  npm run build

  popd >/dev/null || exit 1
fi

echo "[ OK ] Finished activating sub-theme."
