#!/usr/bin/env bash
##
# Assemble a codebase using project code and all required dependencies.
#
# Allows to use the latest Drupal core as well as specified versions (for
# testing backward compatibility).
#
# - Retrieves the scaffold from drupal-composer/drupal-project or custom scaffold.
# - Builds Drupal site codebase with current extension and it's dependencies.
# - Adds development dependencies.
# - Installs composer dependencies.
#
# This script will re-build the codebase from scratch every time it runs.

# shellcheck disable=SC2015,SC2094,SC2002

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Drupal core version to use. If not provided - the latest stable version will be used.
# Must be coupled with DRUPAL_PROJECT_SHA below.
DRUPAL_VERSION="${DRUPAL_VERSION:-10}"

# Commit SHA of the drupal-project to install custom core version. If not
# provided - the latest version will be used.
# Must be coupled with DRUPAL_VERSION above.
DRUPAL_PROJECT_SHA="${DRUPAL_PROJECT_SHA:-10.x}"

# Repository for "drupal-composer/drupal-project" project.
# May be overwritten to use forked repos that may have not been accepted
# yet (i.e., when major Drupal version is about to be released).
DRUPAL_PROJECT_REPO="${DRUPAL_PROJECT_REPO:-https://github.com/drupal-composer/drupal-project.git}"

#-------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

#-------------------------------------------------------------------------------

echo "==============================="
echo "         🏗️ ASSEMBLE           "
echo "==============================="
echo

# Make sure Composer doesn't run out of memory.
export COMPOSER_MEMORY_LIMIT=-1

info "Validate tools."
! command -v git >/dev/null && echo "ERROR: Git is required for this script to run." && exit 1
! command -v php >/dev/null && echo "ERROR: PHP is required for this script to run." && exit 1
! command -v composer >/dev/null && echo "ERROR: Composer (https://getcomposer.org/) is required for this script to run." && exit 1
! command -v jq >/dev/null && echo "ERROR: jq (https://stedolan.github.io/jq/) is required for this script to run." && exit 1
pass "Tools are valid."

# Extension name, taken from the .info file.
extension="$(basename -s .info.yml -- ./*.info.yml)"
[ "${extension}" == "*" ] && echo "ERROR: No .info.yml file found." && exit 1

# Extension type.
type=$(grep -q "type: theme" "${extension}.info.yml" && echo "themes" || echo "modules")

info "Validate Composer configuration."
composer validate --ansi --strict

# Reset the environment.
if [ -d "build" ]; then
  info "Removing existing build directory."
  chmod -Rf 777 "build" >/dev/null || true
  rm -rf "build" >/dev/null || true
  pass "Existing build directory removed."
fi

info "Creating Drupal codebase."
# Allow installing custom version of Drupal core from drupal-composer/drupal-project,
# but only coupled with drupal-project SHA (required to get correct dependencies).
if [ -n "${DRUPAL_VERSION:-}" ] && [ -n "${DRUPAL_PROJECT_SHA:-}" ]; then
  note "Initialising Drupal site from the scaffold repo ${DRUPAL_PROJECT_REPO} commit ${DRUPAL_PROJECT_SHA}."

  # Clone Drupal core at the specific commit SHA.
  git clone -n "${DRUPAL_PROJECT_REPO}" "build"
  git --git-dir="build/.git" --work-tree="build" checkout "${DRUPAL_PROJECT_SHA}"
  rm -rf "build/.git" >/dev/null

  note "Pin Drupal to a specific version ${DRUPAL_VERSION}."
  sed_opts=(-i) && [ "$(uname)" == "Darwin" ] && sed_opts=(-i '')
  sed "${sed_opts[@]}" 's|\(.*"drupal\/core"\): "\(.*\)",.*|\1: '"\"$DRUPAL_VERSION\",|" "build/composer.json"
  cat "build/composer.json"
else
  note "Initialising Drupal site from the latest scaffold."
  # There are no releases in "drupal-composer/drupal-project", so have to use "@dev".
  composer create-project drupal-composer/drupal-project:@dev "build" --no-interaction --no-install
fi
pass "Drupal codebase created."

info "Merging configuration from composer.dev.json."
php -r "echo json_encode(array_replace_recursive(json_decode(file_get_contents('composer.dev.json'), true),json_decode(file_get_contents('build/composer.json'), true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);" >"build/composer2.json" && mv -f "build/composer2.json" "build/composer.json"

info "Merging configuration from extension's composer.json."
php -r "echo json_encode(array_replace_recursive(json_decode(file_get_contents('composer.json'), true),json_decode(file_get_contents('build/composer.json'), true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);" >"build/composer2.json" && mv -f "build/composer2.json" "build/composer.json"

# Asset Packagist sometimes fails, so we remove it by default. If it's needed,
# the lines below can be commented out.
info "Remove asset-packagist"
composer --working-dir="build" config --unset repositories.1 || true

if [ -d "patches" ]; then
  info "Copying patches."
  mkdir -p "build/patches"
  cp -r patches/* "build/patches/"
fi

if [ -n "${GITHUB_TOKEN:-}" ]; then
  info "Adding GitHub authentication token if provided."
  composer config --global github-oauth.github.com "${GITHUB_TOKEN}"
  composer config --global github-oauth.github.com | grep -q "gh" || fail "GitHub token not added."
  pass "GitHub token added."
fi

info "Creating custom directories."
mkdir -p build/web/modules/custom build/web/themes/custom

info "Installing dependencies."
composer --working-dir="build" install
pass "Dependencies installed."

# Suggested dependencies allow to install them for testing without requiring
# them in extension's composer.json.
info "Installing suggested dependencies from extension's composer.json."
composer_suggests=$(cat composer.json | jq -r 'select(.suggest != null) | .suggest | keys[]')
for composer_suggest in $composer_suggests; do
  composer --working-dir="build" require "${composer_suggest}"
done
pass "Suggested dependencies installed."

# If front-end dependencies are used in the project, package-lock.json is
# expected to be committed to the repository.
if [ -f "package-lock.json" ]; then
  info "Installing front-end dependencies."
  if [ -f ".nvmrc" ]; then nvm use || true; fi
  if [ ! -d "node_modules" ]; then npm ci; fi

  echo "> Building front-end dependencies."
  if [ ! -f ".skip_npm_build" ]; then npm run build; fi
  pass "Front-end dependencies installed."
fi

info "Copying tools configuration files."
# Not every tool correctly resolves the path to the configuration file if it is
# symlinked, so we copy them instead.
cp phpcs.xml phpstan.neon phpmd.xml rector.php .twig-cs-fixer.php phpunit.xml "build/"
pass "Tools configuration files copied."

info "Symlinking extension's code."
rm -rf "build/web/${type}/custom" >/dev/null && mkdir -p "build/web/${type}/custom/${extension}"
ln -s "$(pwd)"/* "build/web/${type}/custom/${extension}" && rm "build/web/${type}/custom/${extension}/build"
pass "Extension's code symlinked."

echo
echo "==============================="
echo "    🏗 ASSEMBLE COMPLETE ✅   "
echo "==============================="
echo
echo "> Next steps:"
echo "  .devtools/start.sh # Start the webserver"
echo "  .devtools/provision.sh    # Provision the website"
echo
