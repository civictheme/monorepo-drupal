#!/usr/bin/env bash
##
# Example of the custom per-project command that will run after website is installed.
#
# Clone this file and modify it to your needs or simply remove it.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# Path to the DOCROOT.
WEBROOT="${WEBROOT:-docroot}"

# Drush alias.
DRUSH_ALIAS="${DRUSH_ALIAS:-}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "==> Enabling modules after install."
$drush ${DRUSH_ALIAS} pm-enable shield -y
$drush ${DRUSH_ALIAS} pm-enable cd_core -y

# Perform operations based on the current environment.
if $drush ${DRUSH_ALIAS} ev "print \Drupal\core\Site\Settings::get('environment');" | grep -q -e dev -e test -e ci -e local; then
  echo "==> Enable modules in non-production environment."

  $drush ${DRUSH_ALIAS} pm-enable config_devel -y
fi
