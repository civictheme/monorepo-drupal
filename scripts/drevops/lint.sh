#!/usr/bin/env bash
# shellcheck disable=SC2086
# shellcheck disable=SC2015
##
# Lint code.
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

# Flag to allow BE lint to fail.
DREVOPS_LINT_BE_ALLOW_FAILURE="${DREVOPS_LINT_BE_ALLOW_FAILURE:-0}"

# Flag to allow FE lint to fail.
DREVOPS_LINT_FE_ALLOW_FAILURE="${DREVOPS_LINT_FE_ALLOW_FAILURE:-0}"

# Comma-separated list of PHPCS targets (no spaces).
DREVOPS_LINT_PHPCS_TARGETS="${DREVOPS_LINT_PHPCS_TARGETS:-}"

# PHP Parallel Lint targets as a comma-separated list of extensions with no
# preceding dot or space.
DREVOPS_LINT_PHPLINT_TARGETS="${DREVOPS_LINT_PHPLINT_TARGETS:-}"

# PHP Parallel Lint extensions as a comma-separated list of extensions with
# no preceding dot or space.
DREVOPS_LINT_PHPLINT_EXTENSIONS="${DREVOPS_LINT_PHPLINT_EXTENSIONS:-php,inc,module,theme,install}"

# Drupal theme name.
DREVOPS_DRUPAL_THEME="${DREVOPS_DRUPAL_THEME:-}"

# ------------------------------------------------------------------------------

# Provide argument as 'be' or 'fe' to lint only back-end or front-end code.
# If no argument is provided, all code will be linted.
DREVOPS_LINT_TYPE="${1:-be-fe}"

if [ -z "${DREVOPS_LINT_TYPE##*be*}" ]; then
  # Lint code for coding standards.
  vendor/bin/phpcs ${DREVOPS_LINT_PHPCS_TARGETS//,/ } && \
  # Lint code for mess.
  vendor/bin/phpmd --exclude node_modules/*,vendor/* ${DREVOPS_LINT_PHPMD_TARGETS//, /,} "${DREVOPS_LINT_PHPMD_FORMAT:-text}" ${DREVOPS_LINT_PHPMD_RULESETS// /} || \
  # Flag to allow lint to fail.
  [ "${DREVOPS_LINT_BE_ALLOW_FAILURE}" -eq 1 ]
fi

# Lint code using front-end linter.
if [ -z "${DREVOPS_LINT_TYPE##*fe*}" ] && [ -n "${DREVOPS_DRUPAL_THEME}" ]; then
  # Lint library.
  if grep -q lint "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}/civictheme_library/package.json" && [ -d "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}/civictheme_library/node_modules" ]; then
    npm run --prefix "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}/civictheme_library" lint || \
    # Flag to allow lint to fail.
    [ "${DREVOPS_LINT_FE_ALLOW_FAILURE}" -eq 1 ]
  fi

  # Lint base theme.
  if grep -q lint "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}/package.json" && [ -d "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}/node_modules" ]; then
    npm run --prefix "docroot/themes/contrib/${DREVOPS_DRUPAL_THEME}" lint || \
    # Flag to allow lint to fail.
    [ "${DREVOPS_LINT_FE_ALLOW_FAILURE}" -eq 1 ]
  fi
fi

# Lint theme configuration.
if [ -z "${DREVOPS_LINT_TYPE##*config*}" ]; then
  # Lint theme configuration.
  ./scripts/lint-theme-config.sh
fi
