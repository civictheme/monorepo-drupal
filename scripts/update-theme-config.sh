#!/usr/bin/env bash
##
# Update theme config from the current site config.
#
# Usage:
# ./scripts/update-theme-config.sh
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# File with exclusion patterns.
EXCLUDED_CONFIGS_FILE="scripts/theme_excluded_configs.txt"

# Theme info file.
THEME_INFO="docroot/themes/contrib/civictheme/civictheme.info.yml"

# Temp dir.
TMP_DIR="/tmp/civictheme"

# Temp dir for exported config.
TMP_DIR_EXPORTED="${TMP_DIR}/config_exported"

# Temp file for exported configs.
TMP_FILE="${TMP_DIR}/config_exported_file.txt"

#-------------------------------------------------------------------------------

echo "==> Updating theme config"

[ ! -f "${EXCLUDED_CONFIGS_FILE}" ] && "ERROR: ${EXCLUDED_CONFIGS_FILE} does not exist"
[ ! -f "${THEME_INFO}" ] && "ERROR: ${THEME_INFO} does not exist"

# Remove temp directories that may exist from the previous run.
#rm -Rf "${TMP_DIR_EXPORTED:?}" > /dev/null || true
rm -Rf "${TMP_FILE:?}" > /dev/null || true

# Create temp dirs.
mkdir -p "${TMP_DIR_EXPORTED}" > /dev/null

echo "  > Loading excluded patterns from ${EXCLUDED_CONFIGS_FILE}"
excluded_config_patterns=()
while IFS= read -r; do excluded_config_patterns+=("$REPLY"); done < "${EXCLUDED_CONFIGS_FILE}"

echo "  > Exporting current config to ${TMP_DIR_EXPORTED}"
drush -q cex -y --destination="${TMP_DIR_EXPORTED}"

echo "  > Removing excluded config items"
for exclude_pattern in "${excluded_config_patterns[@]}"; do
  # shellcheck disable=SC2086
  rm -f "${TMP_DIR_EXPORTED}"/${exclude_pattern}.yml || true
done;

echo "  > Compiling a list of theme configs"
echo "  install:" >> "${TMP_FILE}"
for file in "${TMP_DIR_EXPORTED}"/*; do
  filename="$(basename "$file")"
  filename=${filename%".yml"}
  echo "    - $filename" >> "${TMP_FILE}"
done
echo "  optional:" >> "${TMP_FILE}"

echo "  > Writing config to theme info ${THEME_INFO}"
sed -i -e '/install:/,/optional:/!b' -e "/optional:/!d;r ${TMP_FILE}" -e 'd' "${THEME_INFO}"

# Remove config files as the command below does not remove files of deleted config.
theme_dir="$(dirname ${THEME_INFO})"
theme_config_install_dir="${theme_dir}/config/install"
theme_config_optional_dir="${theme_dir}/config/optional"
echo "  > Removing existing theme configs."
rm "${theme_config_install_dir}"/* > /dev/null
rm "${theme_config_optional_dir}"/* > /dev/null

echo "  > Exporting theme configs"
drush -q cde civictheme

echo "==> Finished."

echo
echo "Next steps:"
echo " - Review changes in ${THEME_INFO}."
echo " - Review newly added configs."
echo " - Commit changes."
echo
