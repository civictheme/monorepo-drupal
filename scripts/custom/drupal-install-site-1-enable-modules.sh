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
rm -Rf "${APP}"/docroot/sites/default/files/* > /dev/null || true

echo "  > Enable required modules."
$drush ${DRUSH_ALIAS} -y pm-enable components, field_group, menu_block, inline_form_errors, layout_builder_restrictions, paragraphs, rest, block_content, webform

echo "  > Enable admin theme and set as default."
$drush ${DRUSH_ALIAS} -y then adminimal_theme
$drush ${DRUSH_ALIAS} -y config-set system.theme admin adminimal_theme

echo "  > Enable CivicTheme theme and set as default."
$drush ${DRUSH_ALIAS} -y then civictheme
$drush ${DRUSH_ALIAS} -y config-set system.theme default civictheme
$drush ${DRUSH_ALIAS} -y config-set media.settings standalone_url true

echo "  > Uninstall obsolete themes."
$drush ${DRUSH_ALIAS} -y thun claro
$drush ${DRUSH_ALIAS} -y thun govcms_bartik
$drush ${DRUSH_ALIAS} -y thun bartik

echo "  > Provision content."
$drush ${DRUSH_ALIAS} ev "require_once '/app/docroot/themes/contrib/civictheme/civictheme.provision.inc'; civictheme_provision();"

echo "  > Remove GovCMS configs."
$drush ${DRUSH_ALIAS} -y pm-enable civictheme_govcms
$drush ${DRUSH_ALIAS} civictheme_govcms:remove-config

echo "  > Provision default content."
$drush ${DRUSH_ALIAS} -y pm-enable civictheme_content

echo "  > Enable helper module."
$drush ${DRUSH_ALIAS} -y pm-enable cs_core

echo "  > Generate test content."
GENERATED_CONTENT_CREATE=1 $drush ${DRUSH_ALIAS} -y pm-enable cs_generated_content

echo "  > Generate sitemap."
$drush ${DRUSH_ALIAS} simple-sitemap:generate

if $drush ${DRUSH_ALIAS} ev "print \Drupal\core\Site\Settings::get('environment');" | grep -q -e local; then
  echo "==> Enable modules in non-production environment."
  $drush ${DRUSH_ALIAS} -y pm-enable config_devel
fi
