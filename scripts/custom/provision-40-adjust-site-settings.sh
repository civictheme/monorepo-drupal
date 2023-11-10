#!/usr/bin/env bash
##
# Adjust site settings.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }

echo "[INFO] Adjusting site settings."

drush config:set user.settings register admin_only

drush config:set pathauto.settings max_length 255
drush config:set pathauto.settings max_component_length 255

echo "[ OK ] Finished adjusting site settings."
