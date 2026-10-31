#!/usr/bin/env bash
##
# Create (locally) and activate the civictheme_demo sub-theme.
#
# The sub-theme is generated, never committed. Where it comes from differs by
# environment, because the pipeline invariant says npm never runs on a server:
#   - Deployed image: generated and npm-built by .docker/drupal/Dockerfile
#     (subtheme + assets stages) — this hook only activates it.
#   - Local (bind-mounted src/): generated here via the base theme's
#     civictheme_create_subtheme.php (the app container has php but no node);
#     front-end assets are built on the HOST — the ahoy build wrappers run
#     `ahoy fe-subtheme` after provisioning.
#
# Idempotent — safe on every deploy.
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"

drush() { "$DRUSH" -y "$@"; }

echo "[INFO] Activating sub-theme."

if [ "${CIVICTHEME_SUBTHEME_ACTIVATION_SKIP:-}" = "1" ]; then
  echo "[ OK ] Skipping sub-theme activation."
  exit 0
fi

subtheme_dir="${APP_ROOT}/web/themes/custom/civictheme_demo"
create_args=(civictheme_demo "CivicTheme Demo" "Demo sub-theme for a CivicTheme theme.")
if [ "${CIVICTHEME_INSTALL_SIBLING:-}" = "1" ]; then
  # Sibling variant: the sub-theme lives next to the base theme under
  # themes/contrib rather than in themes/custom.
  subtheme_dir="${APP_ROOT}/web/themes/contrib/civictheme_demo"
  create_args=(civictheme_demo "CivicTheme Demo Sibling" "Demo sub-theme for a CivicTheme theme installed in the same directory." ../civictheme_demo)
fi

if [ ! -d "$subtheme_dir" ]; then
  if [ "${QUANT_ENV_TYPE:-local}" = "local" ]; then
    echo "  > Creating civictheme_demo sub-theme."
    pushd "${APP_ROOT}/web/themes/contrib/civictheme" >/dev/null
    php civictheme_create_subtheme.php "${create_args[@]}" --remove-examples
    popd >/dev/null
    if [ ! -d "$subtheme_dir" ]; then
      echo "[ERROR] Failed to create civictheme_demo sub-theme." >&2
      exit 1
    fi
  else
    echo "[ERROR] ${subtheme_dir} is missing. On a server the sub-theme must be baked into the image — see the subtheme/assets stages in .docker/drupal/Dockerfile." >&2
    exit 1
  fi
fi

echo "  > Installing civictheme_demo theme."
drush theme:enable civictheme_demo

echo "  > Setting civictheme_demo as a default theme."
drush config-set system.theme default civictheme_demo

if [ "${QUANT_ENV_TYPE:-local}" = "local" ] && [ ! -d "${subtheme_dir}/dist" ]; then
  echo "[WARN] ${subtheme_dir}/dist is missing - run 'ahoy fe-subtheme' on the host to build the sub-theme assets."
fi

echo "[ OK ] Finished activating sub-theme."
