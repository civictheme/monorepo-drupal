#!/usr/bin/env bash
#
# Helpers related to common testing functionality.
#
# Run with "--verbose-run" to see debug output.
#

################################################################################
#                       BATS HOOK IMPLEMENTATIONS                              #
################################################################################

setup() {
  # For a list of available variables see:
  # @see https://bats-core.readthedocs.io/en/stable/writing-tests.html#special-variables

  # Register a path to libraries.
  export BATS_LIB_PATH="${BATS_TEST_DIRNAME}/node_modules"

  # Load 'bats-helpers' library.
  bats_load_library bats-helpers

  # Setup command mocking.
  setup_mock

  # Current directory where the test is run from.
  # shellcheck disable=SC2155
  export CUR_DIR="$(pwd)"

  # Print debug information if "--verbose-run" is passed.
  # LCOV_EXCL_START
  if [ "${BATS_VERBOSE_RUN-}" = "1" ]; then
    echo "BUILD_DIR: ${BUILD_DIR}" >&3
  fi
  # LCOV_EXCL_END
}

# Run bats with `--tap` option to debug the output.
debug() {
  echo "${1}" >&3
}
