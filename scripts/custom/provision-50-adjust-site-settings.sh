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

echo "[ OK ] Finished adjusting site settings."
