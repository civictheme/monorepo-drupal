#!/usr/bin/env bash
##
# Run tests.
#

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Webserver hostname.
WEBSERVER_HOST="${WEBSERVER_HOST:-localhost}"

# Webserver port.
WEBSERVER_PORT="${WEBSERVER_PORT:-8000}"

# Directory to store test results.
TEST_RESULTS_DIR="${TEST_RESULTS_DIR:-/tmp/test_results/simpletest}"

#-------------------------------------------------------------------------------

echo "==> Run tests."

# Do not fail if there are no tests.
[ ! -d "tests" ] && echo "==> No tests were found. Skipping." && exit 0

# Module name, taken from .info file.
theme="$(basename -s .info.yml -- ./*.info.yml)"
[ "${theme}" == "*" ] && echo "ERROR: No .info.yml file found." && exit 1

# Test database file path.
test_db_file="/tmp/test_${theme}.sqlite"

# Re-create test results directory.
rm -rf "${TEST_RESULTS_DIR}" > /dev/null
mkdir -p "${TEST_RESULTS_DIR}"

# Remove existing test DB file.
rm -f "${test_db_file}" > /dev/null

# Run tests using script provided by Drupal.
php "./build/web/core/scripts/run-tests.sh" \
  --sqlite "${test_db_file}" \
  --dburl "sqlite://localhost/${test_db_file}" \
  --url "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" \
  --non-html \
  --xml "${TEST_RESULTS_DIR}" \
  --color \
  --verbose \
  --suppress-deprecations \
  --module "${theme}"
