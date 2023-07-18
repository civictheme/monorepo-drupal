#!/usr/bin/env bash
##
# Build Drupal site using SQLite database, install current theme and serve
# using in-built PHP server.
#
# Allows to use the latest Drupal core as well as specified versions (for
# testing backward compatibility).
#
# - Retrieves the scaffold from drupal-composer/drupal-project or custom scaffold.
# - Builds Drupal site codebase with current theme and it's dependencies.
# - Installs Drupal using SQLite database.
# - Starts in-built PHP-server
# - Enables theme
# - Serves site and generates one-time login link
#
# This script will re-build everything from scratch every time it runs.

# shellcheck disable=SC2015,SC2094,SC2002

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Directory where Drupal site will be built.
BUILD_DIR="${BUILD_DIR:-build}"

# Webserver hostname.
WEBSERVER_HOST="${WEBSERVER_HOST:-localhost}"

# Webserver port.
WEBSERVER_PORT="${WEBSERVER_PORT:-8000}"

# Drupal core version to use. If not provided - the latest stable version will be used.
# Must be coupled with DRUPAL_PROJECT_SHA below.
DRUPAL_VERSION="${DRUPAL_VERSION:-}"

# Commit SHA of the drupal-project to install custom core version. If not
# provided - the latest version will be used.
# Must be coupled with DRUPAL_VERSION above.
DRUPAL_PROJECT_SHA="${DRUPAL_PROJECT_SHA:-}"

# Repository for "drupal-composer/drupal-project" project.
# May be overwritten to use forked repos that may have not been accepted
# yet (i.e., when major Drupal version is about to be released).
DRUPAL_PROJECT_REPO="${DRUPAL_PROJECT_REPO:-https://github.com/drupal-composer/drupal-project.git}"

# Drupal profile to use when installing the site.
DRUPAL_PROFILE="${DRUPAL_PROFILE:-standard}"

# Theme name, taken from the .info file.
THEME="$(basename -s .info.yml -- ./*.info.yml)"

# Database file path.
DB_FILE="${DB_FILE:-/tmp/site_${THEME}.sqlite}"

#-------------------------------------------------------------------------------

echo
echo "==> Started build in \"${BUILD_DIR}\" directory."
echo

echo "-------------------------------"
echo " Validating requirements       "
echo "-------------------------------"

echo "  > Validating tools."
! command -v git > /dev/null && echo "ERROR: Git is required for this script to run." && exit 1
! command -v php > /dev/null && echo "ERROR: PHP is required for this script to run." && exit 1
! command -v composer > /dev/null && echo "ERROR: Composer (https://getcomposer.org/) is required for this script to run." && exit 1
! command -v jq > /dev/null && echo "ERROR: jq (https://stedolan.github.io/jq/) is required for this script to run." && exit 1

echo "  > Validating Composer configuration."
composer validate --ansi --strict

# Reset the environment.
[ -d "${BUILD_DIR}" ] && echo "  > Removing existing ${BUILD_DIR} directory." && chmod -Rf 777 "${BUILD_DIR}" && rm -rf "${BUILD_DIR}"

echo "-------------------------------"
echo " Installing Composer packages  "
echo "-------------------------------"

# Allow installing custom version of Drupal core from drupal-composer/drupal-project,
# but only coupled with drupal-project SHA (required to get correct dependencies).
if [ -n "${DRUPAL_VERSION:-}" ] && [ -n "${DRUPAL_PROJECT_SHA:-}" ]; then
  echo "  > Initialising Drupal site from the scaffold repo ${DRUPAL_PROJECT_REPO} commit ${DRUPAL_PROJECT_SHA}."

  # Clone Drupal core at the specific commit SHA.
  git clone -n "${DRUPAL_PROJECT_REPO}" "${BUILD_DIR}"
  git --git-dir="${BUILD_DIR}/.git" --work-tree="${BUILD_DIR}" checkout "${DRUPAL_PROJECT_SHA}"
  rm -rf "${BUILD_DIR}/.git" > /dev/null

  echo "  > Pinning Drupal to a specific version ${DRUPAL_VERSION}."
  sed_opts=(-i) && [ "$(uname)" == "Darwin" ] && sed_opts=(-i '')
  sed "${sed_opts[@]}" 's|\(.*"drupal\/core"\): "\(.*\)",.*|\1: '"\"$DRUPAL_VERSION\",|" "${BUILD_DIR}/composer.json"
  cat "${BUILD_DIR}/composer.json"
else
  echo "  > Initialising Drupal site from the latest scaffold."
  # There are no releases in "drupal-composer/drupal-project", so have to use "@dev".
  php -d memory_limit=-1 "$(command -v composer)" create-project drupal-composer/drupal-project:@dev "${BUILD_DIR}" --no-interaction --no-install
fi

echo "  > Updating scaffold."
cat <<< "$(jq --indent 4 '.extra["enable-patching"] = true' "${BUILD_DIR}/composer.json")" > "${BUILD_DIR}/composer.json"
cat <<< "$(jq --indent 4 '.extra["phpcodesniffer-search-depth"] = 10' "${BUILD_DIR}/composer.json")" > "${BUILD_DIR}/composer.json"

echo "  > Merging configuration from theme's composer.json."
php -r "echo json_encode(array_replace_recursive(json_decode(file_get_contents('composer.json'), true),json_decode(file_get_contents('${BUILD_DIR}/composer.json'), true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);" > "${BUILD_DIR}/composer2.json" && mv -f "${BUILD_DIR}/composer2.json" "${BUILD_DIR}/composer.json"

echo "  > Adding custom patches."
cat <<< "$(jq --indent 4 '.extra.patches = {"drupal/core": {"Builds failing on missing layout column plugin": "https://www.drupal.org/files/issues/2023-07-16/3204271-20-missing-layout-exception.patch"}}' "${BUILD_DIR}/composer.json")" > "${BUILD_DIR}/composer.json"

echo "  > Creating GitHub authentication token if provided."
[ -n "${GITHUB_TOKEN:-}" ] && composer config --global github-oauth.github.com "$GITHUB_TOKEN" && echo "Token: " && composer config --global github-oauth.github.com

echo "  > Installing dependencies."
php -d memory_limit=-1 "$(command -v composer)" --working-dir="${BUILD_DIR}" install

# Suggested dependencies allow to install them for testing without requiring
# them in theme's composer.json.
echo "  > Installing suggested dependencies from theme's composer.json."
composer_suggests=$(cat composer.json | jq -r 'select(.suggest != null) | .suggest | keys[]')
for composer_suggest in $composer_suggests; do
  php -d memory_limit=-1 "$(command -v composer)" --working-dir="${BUILD_DIR}" require "${composer_suggest}"
done

echo "  > Installing other dev dependencies."
php -d memory_limit=-1 "$(command -v composer)" --working-dir="${BUILD_DIR}" require --dev \
  dealerdirect/phpcodesniffer-composer-installer \
  phpspec/prophecy-phpunit:^2 \
  mglaman/drupal-check \
  rector/rector:0.15.13 \
  palantirnet/drupal-rector
cp "${BUILD_DIR}/vendor/palantirnet/drupal-rector/rector.php" "${BUILD_DIR}/."

echo "-------------------------------"
echo " Starting builtin PHP server   "
echo "-------------------------------"

# Stop previously started services.
killall -9 php > /dev/null 2>&1 || true
# Start the PHP webserver.
nohup php -S "${WEBSERVER_HOST}:${WEBSERVER_PORT}" -t "$(pwd)/${BUILD_DIR}/web" "$(pwd)/${BUILD_DIR}/web/.ht.router.php" > /tmp/php.log 2>&1 &
sleep 4 # Waiting for the server to be ready.
netstat_opts='-tulpn'; [ "$(uname)" == "Darwin" ] && netstat_opts='-anv' || true;
# Check that the server was started.
netstat "${netstat_opts[@]}" | grep -q "${WEBSERVER_PORT}" || (echo "ERROR: Unable to start inbuilt PHP server" && cat /tmp/php.log && exit 1)
# Check that the server can serve content.
curl -s -o /dev/null -w "%{http_code}" -L -I "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" | grep -q 200 || (echo "ERROR: Server is started, but site cannot be served" && exit 1)
echo "  > Started builtin PHP server at http://${WEBSERVER_HOST}:${WEBSERVER_PORT} in $(pwd)/${BUILD_DIR}/web."

echo "-------------------------------"
echo " Installing Drupal and themes "
echo "-------------------------------"

echo "  > Installing Drupal into SQLite database ${DB_FILE}."
"${BUILD_DIR}/vendor/bin/drush" -r "${BUILD_DIR}/web" si "${DRUPAL_PROFILE}" -y --db-url "sqlite://${DB_FILE}" --account-name=admin install_configure_form.enable_update_status_module=NULL install_configure_form.enable_update_status_emails=NULL
"${BUILD_DIR}/vendor/bin/drush" -r "$(pwd)/${BUILD_DIR}/web" status

echo "  > Symlinking theme code."
rm -rf "${BUILD_DIR}/web/themes/${THEME}"/* > /dev/null
mkdir -p "${BUILD_DIR}/web/themes/${THEME}"
ln -s "$(pwd)"/* "${BUILD_DIR}/web/themes/${THEME}" && rm "${BUILD_DIR}/web/themes/${THEME}/${BUILD_DIR}"

########################################

echo "  > Enabling theme ${THEME} dependent modules."
"${BUILD_DIR}/vendor/bin/drush" php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enabling theme ${THEME}."
"${BUILD_DIR}/vendor/bin/drush" -r "${BUILD_DIR}/web" theme:install "${THEME}" -y
"${BUILD_DIR}/vendor/bin/drush" -r "${BUILD_DIR}/web" cr

echo "  > Setting theme ${THEME} as default."
"${BUILD_DIR}/vendor/bin/drush" config:set system.theme default "${THEME}" -y

echo "  > Provisioning content from theme defaults."
"${BUILD_DIR}/vendor/bin/drush" php:eval -v "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_provision_cli();"

########################################

# Visit site to pre-warm caches.
curl -s "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" > /dev/null

echo
echo "-------------------------------"
echo " Build finished 🚀🚀🚀"
echo "-------------------------------"
echo
echo "  > Site URL:            http://${WEBSERVER_HOST}:${WEBSERVER_PORT}"
echo -n "  > One-time login link: "
"${BUILD_DIR}/vendor/bin/drush" -r "${BUILD_DIR}/web" -l "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" uli --no-browser
echo
echo "  > Available commands:"
echo "    ahoy build  # rebuild"
echo "    ahoy lint   # check code standards"
echo "    ahoy test   # run tests"
echo
