#!/usr/bin/env bash
##
# Lint theme configuration
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Theme dir.
THEME_DIR="docroot/themes/contrib/civictheme"

# Config file patterns whose config may change.
DIFF_EXCLUDE_PATTERNS=(
  block.block.civictheme_*
)

# Theme config dir.
THEME_CONFIG_DIR="${THEME_DIR}/config/install"

# Temp dir.
TMP_DIR="/tmp/civictheme"

# Temp dir for current config.
TMP_DIR_CURRENT="${TMP_DIR}/current"

# Temp dir for exported config.
TMP_DIR_EXPORTED="${TMP_DIR}/exported"

#-------------------------------------------------------------------------------
site_is_installed="$(drush status --fields=bootstrap | grep -q "Successful" && echo "1" || echo "0")"

if [ "${site_is_installed}" != "1" ]; then
  echo "==> Site is not installed. Skipping config lint." && exit 0
fi

echo "==> Comparing configuration files"

[ ! -d "${THEME_DIR}" ] && "ERROR: ${THEME_DIR} does not exist"
[ ! -d "${THEME_CONFIG_DIR}" ] && "ERROR: ${THEME_DIR} does not exist"

# Remove temp directories that may exist from the previous run.
rm -Rf "${TMP_DIR_CURRENT:?}" > /dev/null || true
rm -Rf "${TMP_DIR_EXPORTED:?}" > /dev/null || true

# Create temp dirs.
mkdir -p "${TMP_DIR_CURRENT}" > /dev/null
mkdir -p "${TMP_DIR_EXPORTED}" > /dev/null

# Copy current config into a temp dir for comparison.
cp -R "${THEME_CONFIG_DIR}"/* "${TMP_DIR_CURRENT}" > /dev/null

# Remove config dir.
rm -Rf "${THEME_CONFIG_DIR:?}"/* > /dev/null

# Export config.
drush cde civictheme > /dev/null

# Copy exported config into a temp dir for comparison.
cp -R "${THEME_CONFIG_DIR}"/* "${TMP_DIR_EXPORTED}" > /dev/null

# Put back original config.
rm -Rf "${THEME_CONFIG_DIR:?}"/* > /dev/null
cp -R "${TMP_DIR_CURRENT}"/* "${THEME_CONFIG_DIR}" > /dev/null

# Create options array for diff with exclusion patterns.
diff_opts=(-ar)
for exclude_pattern in "${DIFF_EXCLUDE_PATTERNS[@]}"; do
  diff_opts+=( -x "${exclude_pattern}")
done;

# Compare directories.
diff "${diff_opts[@]}" "${TMP_DIR_CURRENT}/" "${TMP_DIR_EXPORTED}/" || ( echo "ERROR: Configuration is not consistent. Are all configuration entries present in civictheme.info.yml?" && exit 1 )

echo "  > OK: Configuration is consistent."

echo "==> Checking for UUIDs"

found=0
for file in "${THEME_CONFIG_DIR}"/*; do
  head -n 1 "${file}" | grep -q 'uuid: ' && ((++found)) && echo "${file}"
done

[ "${found}" -ne 0 ] && echo "ERROR: Found ${found} files with UUIDs in configuration." && exit 1

echo "  > OK: UUIDs were not found in configuration files."
