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

$drush ${DRUSH_ALIAS} -y pm-enable components, field_group, menu_block, inline_form_errors, layout_builder_restrictions, paragraphs, rest, block_content, webform

$drush ${DRUSH_ALIAS} -y then adminimal_theme
$drush ${DRUSH_ALIAS} -y config-set system.theme admin adminimal_theme

$drush ${DRUSH_ALIAS} -y then civictheme
$drush ${DRUSH_ALIAS} -y config-set system.theme default civictheme
$drush ${DRUSH_ALIAS} -y config-set media.settings standalone_url true

$drush ${DRUSH_ALIAS} -y thun claro
$drush ${DRUSH_ALIAS} -y thun govcms_bartik
$drush ${DRUSH_ALIAS} -y thun bartik

$drush ${DRUSH_ALIAS} -y pm-enable civictheme_govcms
$drush ${DRUSH_ALIAS} -y pm-enable civictheme_content

$drush ${DRUSH_ALIAS} -y pm-enable cs_core
CS_DEMO_CREATE=1 $drush ${DRUSH_ALIAS} -y pm-enable cs_demo

# Perform operations based on the current environment.
if $drush ${DRUSH_ALIAS} ev "print \Drupal\core\Site\Settings::get('environment');" | grep -q -e dev -e test -e ci -e local; then
  echo "==> Enable modules in non-production environment."

  $drush ${DRUSH_ALIAS} -y pm-enable config_devel
fi
