#!/usr/bin/env bash
##
# Run tests.
#

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

# Module name, taken from .info file.
MODULE="$(basename -s .info.yml -- ./*.info.yml)"

# Test database file path.
TEST_DB_FILE="${TEST_DB_FILE:-/tmp/test.sqlite}"

# Directory to store test results.
TEST_RESULTS_DIR="${TEST_RESULTS_DIR:-/tmp/test_results/simpletest}"

#-------------------------------------------------------------------------------

echo "==> Run tests."

# Do not fail if there are no tests.
[ ! -d "tests" ] && echo "==> No tests were found. Skipping." && exit 0

# Re-create test results directory.
rm -rf "${TEST_RESULTS_DIR}" > /dev/null
mkdir -p "${TEST_RESULTS_DIR}"

# Remove existing test DB file.
rm -f "${TEST_DB_FILE}" > /dev/null

# Run tests using script provided by Drupal.
php "./${BUILD_DIR}/web/core/scripts/run-tests.sh" \
  --sqlite "${TEST_DB_FILE}" \
  --dburl "sqlite://localhost/${TEST_DB_FILE}" \
  --url "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" \
  --non-html \
  --xml "${TEST_RESULTS_DIR}" \
  --color \
  --verbose \
  --suppress-deprecations \
  --module "${MODULE}"
