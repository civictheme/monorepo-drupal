#!/usr/bin/env bash
##
# Provision content.
#
# shellcheck disable=SC2086

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Path to the application.
APP="${APP:-/app}"

# ------------------------------------------------------------------------------

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

if [ "${DREVOPS_DRUPAL_PROFILE}" = "govcms" ]; then
  echo "  > Remove GovCMS configs."
  $drush -y pm-enable civictheme_govcms
  $drush civictheme_govcms:remove-config
else
  echo "  > Enable Admin module."
  $drush -y pm-enable civictheme_admin
fi

echo "  > Provision default content."
$drush -y pm-enable civictheme_content

echo "  > Provision content."
$drush php:eval -v "require_once '/app/docroot/themes/contrib/civictheme/theme-settings.provision.inc'; civictheme_provision_cli();"

echo "  > Enable development module."
$drush -y pm-enable civictheme_dev

echo "  > Generate test content."
GENERATED_CONTENT_CREATE=1 $drush -y pm-enable cs_generated_content

if $drush pm-list --status=enabled | grep -q simple_sitemap; then
  echo "  > Generate sitemap."
  $drush simple-sitemap:generate
fi
