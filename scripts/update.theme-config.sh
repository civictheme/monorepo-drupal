#!/usr/bin/env bash
##
# Update theme config from the current site config.
#
# Theme-owned config (civictheme_starter.settings and the block placements) is the
# curated list in civictheme_starter.info.yml's config_devel block: this script
# re-exports those items via config_devel. The content model lives in the
# recipes under the theme's recipes/ directory and is exported separately by
# ./scripts/update.recipe-config.sh.
#
# Usage:
# ./scripts/update.theme-config.sh
#

set -eu
[ -n "${SCAFFOLD_DEBUG:-}" ] && set -x

# Theme info file.
THEME_INFO="web/themes/contrib/civictheme_starter/civictheme_starter.info.yml"

#-------------------------------------------------------------------------------

echo "==> Updating theme config"
echo "==> "
echo "==> !!! IMPORTANT !!!"
echo "==> "
echo "==> Make sure that CivicTheme theme configuration is captured."
echo "==> Navigate to Appearance -> CivicTheme and save the form."
echo "==> "

[ ! -f "${THEME_INFO}" ] && "ERROR: ${THEME_INFO} does not exist"

# Remove config files as the command below does not remove files of deleted
# config.
theme_dir="$(dirname ${THEME_INFO})"
theme_config_install_dir="${theme_dir}/config/install"
theme_config_optional_dir="${theme_dir}/config/optional"
echo "  > Removing existing theme configs."
rm "${theme_config_install_dir}"/* >/dev/null
rm "${theme_config_optional_dir}"/* >/dev/null

echo "  > Exporting theme configs"
drush -q cde civictheme_starter

echo "  > Exporting recipe configs"
./scripts/update.recipe-config.sh

echo "==> Finished."

echo
echo "Next steps:"
echo " - Review newly exported configs (theme config/ and recipes */config)."
echo " - Commit changes."
echo
