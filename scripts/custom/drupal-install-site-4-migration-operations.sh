#!/usr/bin/env bash
##
# Migration operations that will run after website is installed.
#

set -eu
[ -n "${DREVOPS_DEBUG:-}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# Flag to completely skip migrations.
MIGRATION_SKIP="${MIGRATION_SKIP:-0}"

# Limit of migration items.
MIGRATION_IMPORT_LIMIT="${MIGRATION_IMPORT_LIMIT:-all}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "==> Started migration post site install operations."
environment="$($drush ev "print \Drupal\core\Site\Settings::get('environment');")"
echo "    Environment:     ${environment}"
echo "    Migration skip:  ${MIGRATION_SKIP}"
echo "    Migration limit: ${MIGRATION_IMPORT_LIMIT}"
echo

if [ "${MIGRATION_SKIP}" = "1" ]; then
  echo "==> Skipping Migration post site install operations."
  return
fi

if [ -n "${DREVOPS_DRUPAL_INSTALL_USE_MAINTENANCE_MODE}" ]; then
  echo "  > Disabling maintenance mode before running core group migrations."
  $drush state:set system.maintenance_mode 1 --input-format=integer -y
fi

echo "==> Running migrations."

# Define pairs of migration type and URL.
# @see https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/project/governmentcivicthemeio
$drush config-set "migrate_plus.migration.media_civictheme_image"                            -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-129/media-civictheme_image.json]"
$drush config-set "migrate_plus.migration.media_civictheme_icon"                             -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-132/media-civictheme_icon.json]"
$drush config-set "migrate_plus.migration.media_civictheme_document"                         -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-130/media-civictheme_document.json]"
$drush config-set "migrate_plus.migration.menu_link_content_civictheme_primary_navigation"   -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-134/civictheme-primary-navigation.json]"
$drush config-set "migrate_plus.migration.menu_link_content_civictheme_secondary_navigation" -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-135/civictheme-secondary-navigation.json]"
$drush config-set "migrate_plus.migration.menu_link_content_civictheme_footer"               -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-136/civictheme-footer.json]"
$drush config-set "migrate_plus.migration.node_civictheme_page"                              -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-131/governmentcivicthemeio-extractor-page.json]"
$drush config-set "migrate_plus.migration.node_civictheme_page_annotate"                     -y --input-format=yaml source.urls "[https://nginx-php.master.merlin-ui.lagoon.salsa.hosting/sites/default/files/extracted/node-131/governmentcivicthemeio-extractor-page.json]"

$drush cr
$drush migrate:status --group=civictheme_migrate -y
$drush migrate:import --group=civictheme_migrate --update --limit="${MIGRATION_IMPORT_LIMIT}" -y

if [ -n "${DREVOPS_DRUPAL_INSTALL_USE_MAINTENANCE_MODE}" ]; then
  echo "  > Disabling maintenance mode before running core group migrations."
  $drush state:set system.maintenance_mode 0 --input-format=integer -y
fi

echo "==> Finished migration post site install operations."
