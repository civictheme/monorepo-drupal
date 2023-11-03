#!/usr/bin/env bash
##
# Provision content.
#
# shellcheck disable=SC2086

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

drush() { ./vendor/bin/drush -y "$@"; }

echo "[INFO] Finished provisioning content."

if [ -n "${CIVICTHEME_CONTENT_PROFILE:-}" ]; then
  echo "  > Provisioning content from \"${CIVICTHEME_CONTENT_PROFILE}\" content profile."
  drush pm-enable civictheme_content
else
  echo "  > Provisioning content from theme defaults."
  drush php:eval -v "require_once '/app/web/themes/contrib/civictheme/theme-settings.provision.inc'; civictheme_provision_cli();"
fi

if [ "${CIVICTHEME_GENERATED_CONTENT_CREATE_SKIP:-}" != "1" ]; then
  echo "  > Generate test content."
  GENERATED_CONTENT_CREATE=1 drush pm-enable cs_generated_content

  if drush pm-list --status=enabled | grep -q simple_sitemap; then
    echo "  > Generate sitemap."
    drush simple-sitemap:generate
  fi
else
  echo "  > Skipped creation of generated content."
fi

echo "[ OK ] Finished provisioning content."
