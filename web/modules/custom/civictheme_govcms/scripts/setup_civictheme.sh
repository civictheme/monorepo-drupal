#!/usr/bin/env bash

# --- CivicTheme Setup Script for GovCMS ---
#
# This script automates the setup of CivicTheme, the CivicTheme GovCMS module,
# and a custom subtheme on a GovCMS website.
#
# It assumes:
#   - You are running this script from the root of your Drupal project.
#   - `ahoy` is installed and configured for your project.
#   - Docker and docker compose are installed and running.
#   - `curl` and `tar` are available.
#   - `npm` is available in the cli container.

# --- Strict Mode ---
set -e # Exit immediately if a command exits with a non-zero status.
set -u # Treat unset variables as an error when substituting.
set -o pipefail # The return value of a pipeline is the status of the last command to exit with a non-zero status.

self=$0 # Path to the script itself

# --- Variables for Arguments (will be populated by getopts) ---
ARG_CIVICTHEME_VERSION=""
ARG_GOVCMS_MODULE_REF=""
ARG_SUBTHEME_MACHINE_NAME=""
ARG_SUBTHEME_HUMAN_NAME=""
ARG_SUBTHEME_DESCRIPTION=""
ARG_APPLY_CACHE_PATCH=false

# --- Usage Function ---
usage () {
  cat <<HELP_USAGE
CivicTheme Setup Script for GovCMS

This script automates the setup of CivicTheme, the CivicTheme GovCMS module,
and a custom subtheme on a GovCMS website.

Usage:
  $self -c <civictheme_version> -g <govcms_module_ref> -m <subtheme_machine_name> -u "<subtheme_human_name>" -d "<subtheme_description>" [-p]
  $self -h

Options:
  -h    Print this help message and exit.
  -c    CivicTheme version (e.g., "1.11.0"). (Required)
  -g    CivicTheme GovCMS module Git reference. This can be a branch name (e.g., "main")
        or a tag (e.g., "1.0.1" or "v1.0.1"). (Required)
  -m    Machine name for the new subtheme (e.g., "my_custom_theme").
        Should be alphanumeric lowercase, hyphens allowed. (Required)
  -u    Human-readable name for the new subtheme (e.g., "My Custom Theme"). (Required)
  -d    Description for the new subtheme. (Required)
  -p    Apply Drupal cache backend patch (optional). This patches LayoutPluginManager
        to add cache tags for better cache invalidation.

Example:
  $self -c "1.11.0" -g "main" -m "my_gov_theme" -u "My Awesome Gov Theme" -d "A custom subtheme for GovCMS."
  $self -c "1.11.0" -g "main" -m "my_gov_theme" -u "My Awesome Gov Theme" -d "A custom subtheme for GovCMS." -p
HELP_USAGE
  exit 2
}

# --- Finish Function ---
finish () {
  # Revert the patch if it was applied
  if [ "$ARG_APPLY_CACHE_PATCH" = true ]; then
    echo "[info]: Reverting Drupal cache backend patch..."
    if docker compose exec php sed -i '50s/setCacheBackend(\$cache_backend, \$type, \['\''config:core.extension'\''\]);/setCacheBackend(\$cache_backend, \$type);/' web/core/lib/Drupal/Core/Layout/LayoutPluginManager.php; then
      echo "[success]: Drupal cache backend patch reverted."
    else
      echo "[warn]: Failed to revert Drupal cache backend patch."
    fi
  fi

  echo "[success]: CivicTheme setup process completed!"
  echo "[info]:   CivicTheme Version:         ${ARG_CIVICTHEME_VERSION}"
  echo "[info]:   CivicTheme GovCMS Module Ref: ${ARG_GOVCMS_MODULE_REF}"
  echo "[info]:   Subtheme Machine Name:      ${ARG_SUBTHEME_MACHINE_NAME}"
  echo "[info]:   Subtheme Human Name:        ${ARG_SUBTHEME_HUMAN_NAME}"
}

# --- Parse Command-Line Options ---
while getopts 'hpc:g:m:u:d:' o; do
  case $o in
    c) ARG_CIVICTHEME_VERSION="$OPTARG" ;;
    g) ARG_GOVCMS_MODULE_REF="$OPTARG" ;;
    m) ARG_SUBTHEME_MACHINE_NAME="$OPTARG" ;;
    u) ARG_SUBTHEME_HUMAN_NAME="$OPTARG" ;;
    d) ARG_SUBTHEME_DESCRIPTION="$OPTARG" ;;
    p) ARG_APPLY_CACHE_PATCH=true ;;
    h|?) usage ;;
  esac
done
shift $((OPTIND - 1)) # Remove parsed options from positional parameters

# --- Validate Arguments ---
if [[ -z "$ARG_CIVICTHEME_VERSION" || \
      -z "$ARG_GOVCMS_MODULE_REF" || \
      -z "$ARG_SUBTHEME_MACHINE_NAME" || \
      -z "$ARG_SUBTHEME_HUMAN_NAME" || \
      -z "$ARG_SUBTHEME_DESCRIPTION" ]]; then
  echo "[error]: All arguments (-c, -g, -m, -u, -d) are required."
  usage
fi

# Validate CivicTheme version format (e.g., X.Y.Z)
if ! [[ "$ARG_CIVICTHEME_VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "[error]: Invalid CivicTheme version format for '-c ${ARG_CIVICTHEME_VERSION}'. Expected format like '1.11.0'."
  usage
fi

# Validate Subtheme machine name (alphanumeric lowercase, hyphens allowed)
if [[ "$ARG_SUBTHEME_MACHINE_NAME" =~ [^a-z0-9\-_] || -z "$ARG_SUBTHEME_MACHINE_NAME" ]]; then
  echo "[error]: Invalid subtheme machine name for '-m ${ARG_SUBTHEME_MACHINE_NAME}'. Must be alphanumeric lowercase, hyphens/underscores allowed."
  usage
fi

# --- Main Script Logic ---
echo "[info]: Starting CivicTheme setup for GovCMS..."
echo "[info]: Parameters:"
echo "[info]:   CivicTheme Version:         ${ARG_CIVICTHEME_VERSION}"
echo "[info]:   CivicTheme GovCMS Module Ref: ${ARG_GOVCMS_MODULE_REF}"
echo "[info]:   Subtheme Machine Name:      ${ARG_SUBTHEME_MACHINE_NAME}"
echo "[info]:   Subtheme Human Name:        \"${ARG_SUBTHEME_HUMAN_NAME}\""
echo "[info]:   Subtheme Description:       \"${ARG_SUBTHEME_DESCRIPTION}\""
echo "[info]:   Apply Cache Patch:          ${ARG_APPLY_CACHE_PATCH}"
echo "---"

# --- Apply patch if requested ---
if [ "$ARG_APPLY_CACHE_PATCH" = true ]; then
  echo "[info]: Applying Drupal cache backend patch..."
  if docker compose exec php sed -i '50s/\$this->setCacheBackend(\$cache_backend, \$type);/\$this->setCacheBackend(\$cache_backend, \$type, ['\''config:core.extension'\'']);/' web/core/lib/Drupal/Core/Layout/LayoutPluginManager.php; then
    echo "[success]: Drupal cache backend patch applied."
  else
    echo "[error]: Failed to apply Drupal cache backend patch."
    exit 1
  fi
  echo "---"
fi

# --- 1. Extract CivicTheme ---
THEME_DOWNLOAD_URL="https://ftp.drupal.org/files/projects/civictheme-${ARG_CIVICTHEME_VERSION}.tar.gz"
THEME_ARCHIVE_NAME="civictheme-${ARG_CIVICTHEME_VERSION}.tar.gz"
CUSTOM_THEMES_PATH="themes/custom" # Relative to project root
EXPECTED_EXTRACTED_THEME_DIR="${CUSTOM_THEMES_PATH}/civictheme"

echo "[info]: Step 1: Downloading and extracting CivicTheme version ${ARG_CIVICTHEME_VERSION}..."
mkdir -p "${CUSTOM_THEMES_PATH}"

# Clean up existing theme directory if it exists, to ensure a fresh install
if [ -d "${EXPECTED_EXTRACTED_THEME_DIR}" ]; then
    echo "[warn]: Existing directory '${EXPECTED_EXTRACTED_THEME_DIR}' found. Removing it for a fresh setup."
    rm -rf "${EXPECTED_EXTRACTED_THEME_DIR}"
fi

echo "[info]: Downloading ${THEME_DOWNLOAD_URL}..."
if ! curl -L -o "${CUSTOM_THEMES_PATH}/${THEME_ARCHIVE_NAME}" "${THEME_DOWNLOAD_URL}"; then
  echo "[error]: Failed to download CivicTheme from ${THEME_DOWNLOAD_URL}."
  exit 1
fi

echo "[info]: Extracting ${THEME_ARCHIVE_NAME} to ${CUSTOM_THEMES_PATH}..."
if ! tar -xzf "${CUSTOM_THEMES_PATH}/${THEME_ARCHIVE_NAME}" -C "${CUSTOM_THEMES_PATH}"; then
  echo "[error]: Failed to extract ${THEME_ARCHIVE_NAME}."
  rm -f "${CUSTOM_THEMES_PATH}/${THEME_ARCHIVE_NAME}" # Clean up downloaded archive on extraction failure
  exit 1
fi
rm "${CUSTOM_THEMES_PATH}/${THEME_ARCHIVE_NAME}" # Clean up archive after successful extraction

# Verify extraction: ftp.drupal.org tarballs usually extract directly to 'civictheme'
if [ ! -d "${EXPECTED_EXTRACTED_THEME_DIR}" ]; then
    echo "[error]: Expected directory '${EXPECTED_EXTRACTED_THEME_DIR}' not found after extraction."
    echo "[info]: Listing contents of ${CUSTOM_THEMES_PATH} for debugging:"
    ls -la "${CUSTOM_THEMES_PATH}"
    exit 1
fi
echo "[success]: Step 1: CivicTheme base theme extracted to ${EXPECTED_EXTRACTED_THEME_DIR}."
echo "---"

# --- 2. Run initial theme provisioning Drush command ---
echo "[info]: Step 2: Running CivicTheme provisioning command..."
if ! ahoy drush ev "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"; then
  echo "[error]: CivicTheme provisioning command failed."
  exit 1
fi
echo "[success]: Step 2: CivicTheme provisioning command executed."
echo "---"

# --- 3. Clear Drupal cache (twice) ---
echo "[info]: Step 3: Clearing Drupal cache (twice)..."
if ! (ahoy drush cr && ahoy drush cr); then
  echo "[error]: Failed to clear Drupal cache."
  exit 1
fi
echo "[success]: Step 3: Drupal cache cleared."
echo "---"

# --- 4. Enable CivicTheme and set related configurations ---
echo "[info]: Step 4: Enabling CivicTheme and setting configurations..."
if ! ahoy drush theme:enable civictheme -y; then echo "[error]: Failed to enable civictheme." ; exit 1; fi
if ! ahoy drush config-set -y system.theme default civictheme; then echo "[error]: Failed to set civictheme as default." ; exit 1; fi
if ! ahoy drush config-set -y media.settings standalone_url true; then echo "[error]: Failed to set media.settings standalone_url." ; exit 1; fi
echo "[success]: Step 4: CivicTheme enabled and configured as default."
echo "---"

# --- 5. Setup CivicTheme GovCMS support module (within container) ---
echo "[info]: Step 5: Setting up CivicTheme GovCMS support module..."

CIVICTHEME_GOVCMS_BASE_URL="https://github.com/civictheme/civictheme_govcms/archive/refs"
CIVICTHEME_GOVCMS_ARCHIVE_FILENAME="${ARG_GOVCMS_MODULE_REF}.tar.gz"
CIVICTHEME_GOVCMS_EXTRACTED_DIR_NAME="civictheme_govcms-${ARG_GOVCMS_MODULE_REF}"
CIVICTHEME_GOVCMS_DOWNLOAD_URL=""

if [[ "${ARG_GOVCMS_MODULE_REF}" == v* || "${ARG_GOVCMS_MODULE_REF}" == *.* ]]; then
  echo "[info]:   Using tag '${ARG_GOVCMS_MODULE_REF}' for civictheme_govcms."
  CIVICTHEME_GOVCMS_DOWNLOAD_URL="${CIVICTHEME_GOVCMS_BASE_URL}/tags/${CIVICTHEME_GOVCMS_ARCHIVE_FILENAME}"
else
  echo "[info]:   Using branch '${ARG_GOVCMS_MODULE_REF}' for civictheme_govcms."
  CIVICTHEME_GOVCMS_DOWNLOAD_URL="${CIVICTHEME_GOVCMS_BASE_URL}/heads/${CIVICTHEME_GOVCMS_ARCHIVE_FILENAME}"
fi

ahoy_cli_govcms_script=$(cat <<'EOF'
  set -e
  set -u
  set -o pipefail

  DOWNLOAD_URL="$1"
  ARCHIVE_FILENAME="$2"
  EXTRACTED_DIR_NAME="$3"
  MODULES_DIR="/app/web/themes/custom/custom/civictheme/modules"
  TARGET_MODULE_DIR_NAME="civictheme_govcms"

  echo "[info] (container): Preparing directory: ${MODULES_DIR}"
  mkdir -p "${MODULES_DIR}"
  cd "${MODULES_DIR}"
  echo "[info] (container): Current directory: $(pwd)"

  if [ -d "${TARGET_MODULE_DIR_NAME}" ]; then
    echo "[warn] (container): Existing directory '${TARGET_MODULE_DIR_NAME}' found. Removing it."
    rm -rf "${TARGET_MODULE_DIR_NAME}"
  fi
  if [ -f "${ARCHIVE_FILENAME}" ]; then rm -f "${ARCHIVE_FILENAME}"; fi
  if [ -d "${EXTRACTED_DIR_NAME}" ]; then rm -rf "${EXTRACTED_DIR_NAME}"; fi

  echo "[info] (container): Downloading civictheme_govcms from ${DOWNLOAD_URL}..."
  if ! curl -L -s -o "${ARCHIVE_FILENAME}" "${DOWNLOAD_URL}"; then
    echo "[error] (container): Failed to download civictheme_govcms from ${DOWNLOAD_URL}." >&2
    exit 1
  fi

  echo "[info] (container): Extracting ${ARCHIVE_FILENAME}..."
  if ! tar -xzf "${ARCHIVE_FILENAME}"; then
    echo "[error] (container): Failed to extract ${ARCHIVE_FILENAME}." >&2
    rm -f "${ARCHIVE_FILENAME}"
    exit 1
  fi
  rm "${ARCHIVE_FILENAME}"

  if [ ! -d "${EXTRACTED_DIR_NAME}" ]; then
    echo "[error] (container): Extracted directory '${EXTRACTED_DIR_NAME}' not found." >&2
    ls -la
    exit 1
  fi
  echo "[info] (container): Renaming extracted directory '${EXTRACTED_DIR_NAME}' to '${TARGET_MODULE_DIR_NAME}'..."
  mv "${EXTRACTED_DIR_NAME}" "${TARGET_MODULE_DIR_NAME}"

  echo "[info] (container): Clearing Drush cache..."
  if ! drush cr; then echo "[error] (container): drush cr failed." >&2; exit 1; fi

  echo "[info] (container): Enabling civictheme_govcms module..."
  if ! drush pm-enable -y civictheme_govcms; then echo "[error] (container): drush pm-enable civictheme_govcms failed." >&2; exit 1; fi

  echo "[info] (container): Running civictheme_govcms:remove-config..."
  if ! drush civictheme_govcms:remove-config --preserve=user_roles,workflows; then echo "[error] (container): drush civictheme_govcms:remove-config failed." >&2; exit 1; fi

  echo "[info] (container): Uninstalling civictheme_govcms module..."
  if ! drush pm-uninstall -y civictheme_govcms; then echo "[error] (container): drush pm-uninstall civictheme_govcms failed." >&2; exit 1; fi

  echo "[info] (container): Removing civictheme_govcms module files..."
  rm -Rf "${TARGET_MODULE_DIR_NAME}"
  rm -Rf "${MODULES_DIR}"

  echo "[success] (container): CivicTheme GovCMS module steps completed."

  echo "[success] (container): Removing setup script"
  rm "${self}"
EOF
)

# MODIFICATION: Using direct docker compose command with -T
if ! docker compose exec -T cli bash -s -- \
  "${CIVICTHEME_GOVCMS_DOWNLOAD_URL}" \
  "${CIVICTHEME_GOVCMS_ARCHIVE_FILENAME}" \
  "${CIVICTHEME_GOVCMS_EXTRACTED_DIR_NAME}" <<< "${ahoy_cli_govcms_script}"; then
  echo "[error]: Step 5: CivicTheme GovCMS support module setup phase failed."
  exit 1
fi
echo "[success]: Step 5: CivicTheme GovCMS support module setup phase completed."
echo "---"

# --- 6. Create sub-theme, build, provision, and set as default ---
echo "[info]: Step 6: Creating, building, provisioning sub-theme, and setting as default..." # Updated message

ahoy_cli_subtheme_script=$(cat <<'EOF'
  set -e
  set -u
  set -o pipefail

  SUBTHEME_MACHINE_NAME_ARG="$1"
  SUBTHEME_HUMAN_NAME_ARG="$2"
  SUBTHEME_DESCRIPTION_ARG="$3"
  CIVICTHEME_BASE_PATH="/app/web/themes/custom/custom/civictheme"
  SUBTHEME_PATH_RELATIVE_TO_CUSTOM_THEMES="../${SUBTHEME_MACHINE_NAME_ARG}"
  SUBTHEME_FULL_PATH="/app/web/themes/custom/custom/${SUBTHEME_MACHINE_NAME_ARG}"

  echo "[info] (container): Changing to CivicTheme base theme directory: ${CIVICTHEME_BASE_PATH}"
  cd "${CIVICTHEME_BASE_PATH}"

  if [ -d "${SUBTHEME_FULL_PATH}" ]; then
    echo "[warn] (container): Existing subtheme directory '${SUBTHEME_FULL_PATH}' found. Removing it."
    rm -rf "${SUBTHEME_FULL_PATH}"
  fi

  echo "[info] (container): Creating sub-theme '${SUBTHEME_MACHINE_NAME_ARG}'..."
  if ! php civictheme_create_subtheme.php \
    "${SUBTHEME_MACHINE_NAME_ARG}" \
    "${SUBTHEME_HUMAN_NAME_ARG}" \
    "${SUBTHEME_DESCRIPTION_ARG}" \
    "${SUBTHEME_PATH_RELATIVE_TO_CUSTOM_THEMES}" \
    --remove-examples; then
      echo "[error] (container): Failed to create subtheme '${SUBTHEME_MACHINE_NAME_ARG}'." >&2
      exit 1
  fi

  echo "[info] (container): Changing to subtheme directory: ${SUBTHEME_FULL_PATH}"
  cd "${SUBTHEME_FULL_PATH}"

  echo "[info] (container): Running npm install in subtheme..."
  if ! npm install; then
    echo "[error] (container): npm install failed in ${SUBTHEME_FULL_PATH}." >&2
    exit 1
  fi

  echo "[info] (container): Running npm run build in subtheme..."
  if ! npm run build; then
    echo "[error] (container): npm run build failed in ${SUBTHEME_FULL_PATH}." >&2
    exit 1
  fi

  echo "[info] (container): Enabling sub-theme '${SUBTHEME_MACHINE_NAME_ARG}'..."
  if ! drush theme:enable "${SUBTHEME_MACHINE_NAME_ARG}" -y; then
    echo "[error] (container): Failed to enable subtheme '${SUBTHEME_MACHINE_NAME_ARG}'." >&2
    exit 1
  fi
  echo "[success] (container): Sub-theme created, built, and enabled." # Updated success message

  echo "[info] (container): Setting sub-theme '${SUBTHEME_MACHINE_NAME_ARG}' as default..."
  if ! drush config-set system.theme default "${SUBTHEME_MACHINE_NAME_ARG}" -y; then
    echo "[error] (container): Failed to set sub-theme '${SUBTHEME_MACHINE_NAME_ARG}' as default." >&2
    exit 1
  fi
  echo "[success] (container): Sub-theme set as default."

  echo "[info] (container): Running CivicTheme provision CLI command..."
  # This command refers to the base 'civictheme', ensure paths are correct if run from subtheme dir
  # Or cd back to project root or base theme dir if necessary before this drush command.
  # Assuming drush finds the base theme correctly from any path within the project:
  if ! drush ev "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_provision_cli();"; then
    echo "[error] (container): CivicTheme provision CLI command failed." >&2
    exit 1
  fi
  echo "[success] (container): CivicTheme provision CLI command completed."

EOF
)

# The single docker compose exec call now handles all of 6a, 6b, 6c
if ! docker compose exec -T cli bash -s -- \
  "${ARG_SUBTHEME_MACHINE_NAME}" \
  "${ARG_SUBTHEME_HUMAN_NAME}" \
  "${ARG_SUBTHEME_DESCRIPTION}" <<< "${ahoy_cli_subtheme_script}"; then
  echo "[error]: Step 6: Sub-theme setup process within container failed." # Consolidated error
  exit 1
fi
echo "[success]: Step 6: Sub-theme '${ARG_SUBTHEME_MACHINE_NAME}' fully set up and active." # Consolidated success
echo "---"

# --- Finalize ---
finish

exit 0
