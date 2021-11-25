#!/usr/bin/env bash
##
# Run post-config updates.
#
# These usually run during the site install phase and rely on the already
# enabled modules, but for this project, the cd_core module is not yet
# installed at the time when these updates run, so we have to re-run them again.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# Drush alias.
DRUSH_ALIAS="${DRUSH_ALIAS:-}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

$drush ${DRUSH_ALIAS} updb -y

# Run post-config import updates for the cases when updates rely on imported configuration.
# @see PostConfigImportUpdateHelper::registerPostConfigImportUpdate()
if $drush ${DRUSH_ALIAS} list | grep -q pciu; then
  echo "==> Running post config import updates after cd_core module installed."
  $drush ${DRUSH_ALIAS} post-config-import-update
fi
