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

#-------------------------------------------------------------------------------

echo
echo "==> Started build in \"build\" directory."
echo

echo "-------------------------------"
echo " Validating requirements       "
echo "-------------------------------"

echo "  > Validating tools."
! command -v git > /dev/null && echo "ERROR: Git is required for this script to run." && exit 1
! command -v php > /dev/null && echo "ERROR: PHP is required for this script to run." && exit 1
! command -v composer > /dev/null && echo "ERROR: Composer (https://getcomposer.org/) is required for this script to run." && exit 1
! command -v jq > /dev/null && echo "ERROR: jq (https://stedolan.github.io/jq/) is required for this script to run." && exit 1

drush() { "build/vendor/bin/drush" -r "$(pwd)/build/web" -y "$@"; }

# Theme name, taken from the .info file.
theme="$(basename -s .info.yml -- ./*.info.yml)"
[ "${theme}" == "*" ] && echo "ERROR: No .info.yml file found." && exit 1

# Database file path.
db_file="/tmp/site_${theme}.sqlite"

export COMPOSER_MEMORY_LIMIT=-1

echo "  > Validating Composer configuration."
composer validate --ansi --strict

# Reset the environment.
[ -d "build" ] && echo "  > Removing existing build directory." && chmod -Rf 777 "build" && rm -rf "build"

echo "-------------------------------"
echo " Installing Composer packages  "
echo "-------------------------------"

# Allow installing custom version of Drupal core from drupal-composer/drupal-project,
# but only coupled with drupal-project SHA (required to get correct dependencies).
if [ -n "${DRUPAL_VERSION:-}" ] && [ -n "${DRUPAL_PROJECT_SHA:-}" ]; then
  echo "  > Initialising Drupal site from the scaffold repo ${DRUPAL_PROJECT_REPO} commit ${DRUPAL_PROJECT_SHA}."

  # Clone Drupal core at the specific commit SHA.
  git clone -n "${DRUPAL_PROJECT_REPO}" "build"
  git --git-dir="build/.git" --work-tree="build" checkout "${DRUPAL_PROJECT_SHA}"
  rm -rf "build/.git" > /dev/null

  echo "  > Pinning Drupal to a specific version ${DRUPAL_VERSION}."
  sed_opts=(-i) && [ "$(uname)" == "Darwin" ] && sed_opts=(-i '')
  sed "${sed_opts[@]}" 's|\(.*"drupal\/core"\): "\(.*\)",.*|\1: '"\"$DRUPAL_VERSION\",|" "build/composer.json"
  cat "build/composer.json"
else
  echo "  > Initialising Drupal site from the latest scaffold."
  # There are no releases in "drupal-composer/drupal-project", so have to use "@dev".
  composer create-project drupal-composer/drupal-project:@dev "build" --no-interaction --no-install
fi

echo "  > Updating scaffold."
cat <<< "$(jq --indent 4 '.extra["enable-patching"] = true' "build/composer.json")" > "build/composer.json"
cat <<< "$(jq --indent 4 '.extra["phpcodesniffer-search-depth"] = 10' "build/composer.json")" > "build/composer.json"

echo "  > Merging configuration from theme's composer.json."
php -r "echo json_encode(array_replace_recursive(json_decode(file_get_contents('composer.json'), true),json_decode(file_get_contents('build/composer.json'), true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);" > "build/composer2.json" && mv -f "build/composer2.json" "build/composer.json"

echo "  > Adding custom patches."
cat <<< "$(jq --indent 4 '.extra.patches = {"drupal/core": {"Builds failing on missing layout column plugin": "https://www.drupal.org/files/issues/2023-07-16/3204271-20-missing-layout-exception.patch"}}' "build/composer.json")" > "build/composer.json"

echo "  > Creating GitHub authentication token if provided."
[ -n "${GITHUB_TOKEN:-}" ] && composer config --global github-oauth.github.com "${GITHUB_TOKEN}" && echo "Token: " && composer config --global github-oauth.github.com

echo "  > Installing dependencies."
composer --working-dir="build" install

# Suggested dependencies allow to install them for testing without requiring
# them in theme's composer.json.
echo "  > Installing suggested dependencies from theme's composer.json."
composer_suggests=$(cat composer.json | jq -r 'select(.suggest != null) | .suggest | keys[]')
for composer_suggest in $composer_suggests; do
  composer --working-dir="build" require "${composer_suggest}"
done

echo "  > Installing other dev dependencies."
composer --working-dir="build" config allow-plugins.phpstan/extension-installer true
composer --working-dir="build" require --dev \
  dealerdirect/phpcodesniffer-composer-installer \
  phpspec/prophecy-phpunit:^2 \
  phpstan/extension-installer \
  phpcompatibility/php-compatibility \
  phpmd/phpmd \
  mglaman/phpstan-drupal:^1.2 \
  palantirnet/drupal-rector:^0.18 \
  friendsoftwig/twigcs:^6.2

echo "  > Copying tools configuration files to the build root directory."
cp phpcs.xml phpstan.neon phpmd.xml rector.php .twig_cs.php "build/"

echo "  > Symlinking theme code."
rm -rf "build/web/themes/custom" > /dev/null && mkdir -p "build/web/themes/custom/${theme}"
ln -s "$(pwd)"/* "build/web/themes/custom/${theme}" && rm "build/web/themes/custom/${theme}/build"

if [ -f "build/web/themes/custom/${theme}/package-lock.json" ]; then
  pushd "build/web/themes/custom/${theme}/" > /dev/null || exit 1
  echo "  > Installing theme assets."
  [ -f ".nvm" ] && nvm use || true
  [ ! -d "node_modules" ] && npm ci || true
  popd > /dev/null || exit 1
fi

echo "-------------------------------"
echo " Starting builtin PHP server   "
echo "-------------------------------"

# Stop previously started services.
killall -9 php > /dev/null 2>&1 || true
# Start the PHP webserver.
nohup php -S "${WEBSERVER_HOST}:${WEBSERVER_PORT}" -t "$(pwd)/build/web" "$(pwd)/build/web/.ht.router.php" > /tmp/php.log 2>&1 &
sleep 4 # Waiting for the server to be ready.
netstat_opts='-tulpn'; [ "$(uname)" == "Darwin" ] && netstat_opts='-anv' || true;
# Check that the server was started.
netstat "${netstat_opts[@]}" | grep -q "${WEBSERVER_PORT}" || (echo "ERROR: Unable to start inbuilt PHP server" && cat /tmp/php.log && exit 1)
# Check that the server can serve content.
curl -s -o /dev/null -w "%{http_code}" -L -I "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" | grep -q 200 || (echo "ERROR: Server is started, but site cannot be served" && exit 1)
echo "  > Started builtin PHP server at http://${WEBSERVER_HOST}:${WEBSERVER_PORT} in $(pwd)/build/web."

echo "-------------------------------"
echo " Installing Drupal and themes "
echo "-------------------------------"

echo "  > Installing Drupal into SQLite database ${db_file}."
drush si "${DRUPAL_PROFILE}" -y --db-url "sqlite://${db_file}" --account-name=admin install_configure_form.enable_update_status_theme=NULL install_configure_form.enable_update_status_emails=NULL
drush status

########################################

echo "  > Enabling theme ${theme} dependent modules."
drush -r "build/web" php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_enable_modules();"

echo "  > Enabling theme ${theme}."
drush -r "build/web" theme:install "${theme}" -y
drush -r "build/web" cr

echo "  > Setting theme ${theme} as default."
drush -r "build/web" config:set system.theme default "${theme}" -y

echo "  > Provisioning content from theme defaults."
drush -r "build/web" php:eval "require_once dirname(\Drupal::getContainer()->get('theme_handler')->rebuildThemeData()['civictheme']->getPathname()) . '/theme-settings.provision.inc'; civictheme_provision_cli();"

########################################

echo "  > Enabling suggested modules, if any."
drupal_suggests=$(cat composer.json | jq -r 'select(.suggest != null) | .suggest | keys[]' | sed "s/drupal\///" | cut -f1 -d":")
for drupal_suggest in $drupal_suggests; do
  drush pm:enable "${drupal_suggest}" -y
done

# Visit site to pre-warm caches.
curl -s "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" > /dev/null

echo
echo "-------------------------------"
echo " Build finished 🚀🚀🚀"
echo "-------------------------------"
echo
echo "  > Site URL:            http://${WEBSERVER_HOST}:${WEBSERVER_PORT}"
echo -n "  > One-time login link: "
drush -l "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" uli --no-browser
echo
echo "  > Available commands:"
echo "    ahoy build  # rebuild"
echo "    ahoy lint   # check code standards"
echo "    ahoy test   # run tests"
echo
