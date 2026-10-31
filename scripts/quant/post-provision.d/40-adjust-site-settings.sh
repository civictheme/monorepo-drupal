#!/usr/bin/env bash
##
# Adjust site settings.
#
# Post-provision hook. Idempotent — safe on every deploy.
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"

drush() { "$DRUSH" -y "$@"; }

echo "[INFO] Adjusting site settings."

drush config:set user.settings register admin_only

drush config:set pathauto.settings max_length 255
drush config:set pathauto.settings max_component_length 255

echo "[ OK ] Finished adjusting site settings."
