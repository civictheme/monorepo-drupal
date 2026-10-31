#!/usr/bin/env bash
##
# Provision content.
#
# Post-provision hook. Only valid right after a fresh `drush site:install`
# (content creation is not idempotent), so it no-ops unless provision.sh set
# the fresh-provision marker.
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"

drush() { "$DRUSH" -y "$@"; }

if [ "${SCAFFOLD_FRESH_PROVISION:-}" != "1" ]; then
  echo "[ OK ] Not a fresh provision - skipping content provisioning."
  exit 0
fi

echo "[INFO] Provisioning content."

if [ -n "${CIVICTHEME_CONTENT_PROFILE:-}" ]; then
  echo "  > Provisioning content from \"${CIVICTHEME_CONTENT_PROFILE}\" content profile."
  drush pm-enable civictheme_content
else
  echo "  > Provisioning content from theme defaults."
  drush php:eval -v "require_once '${APP_ROOT}/web/themes/contrib/civictheme/theme-settings.provision.inc'; civictheme_provision_cli();"
fi

if [ "${CIVICTHEME_GENERATED_CONTENT_CREATE_SKIP:-}" != "1" ]; then
  echo "  > Generate test content."
  drush recipe "${APP_ROOT}/recipes/civictheme_content_generated_static"
  if "$DRUSH" pm-list --status=enabled | grep -q simple_sitemap; then
    echo "  > Generate sitemap."
    drush simple-sitemap:generate
  fi
else
  echo "  > Skipped creation of generated content."
fi

echo "[ OK ] Finished provisioning content."
