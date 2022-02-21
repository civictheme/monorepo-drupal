#!/usr/bin/env bash
##
# Enable modules.
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

echo "==> Removing all files."
rm -Rf "${APP}"/docroot/sites/default/files/* > /dev/null

# Use cd_core.info.yml to declare all dependencies that must be installed in all environments.
# Use cd_core.install to add other post-install operations.
# Use cd_core.post_update.php to content-related post-install operations.
$drush ${DRUSH_ALIAS} pm-enable cd_core -y

$drush ${DRUSH_ALIAS} pm-enable civic_govcms -y
$drush ${DRUSH_ALIAS} pm-enable civic_default_content -y

# Perform operations based on the current environment.
if $drush ${DRUSH_ALIAS} ev "print \Drupal\core\Site\Settings::get('environment');" | grep -q -e dev -e test -e ci -e local; then
  echo "==> Enable modules in non-production environment."

  $drush ${DRUSH_ALIAS} pm-enable config_devel -y
fi
