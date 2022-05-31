#!/usr/bin/env bash
#
# Helpers related to CivicTheme common testing functionality.
#
# shellcheck disable=SC2155

load "${BASH_SOURCE[0]%/*}"/_mock.bash

################################################################################
#                          HOOK IMPLEMENTATIONS                                #
################################################################################

setup() {
  export CUR_DIR="$(pwd)"
  BUILD_DIR="$BATS_TEST_TMPDIR/civictheme-$(random_string)"
  export BUILD_DIR
  prepare_fixture_dir "${BUILD_DIR}"
  echo "BUILD_DIR dir: ${BUILD_DIR}" >&3

  # Setup command mocking.
  setup_mock

  pushd "${BUILD_DIR}" >/dev/null || exit 1
}

teardown() {
  restore_global_gitignore
  popd >/dev/null || cd "${CUR_DIR}" || exit 1
}

################################################################################
#                            COMMAND MOCKING                                   #
################################################################################

# Setup mock support.
# Call this function from your test's setup() method.
setup_mock() {
  # Command and functions mocking support.
  # @see https://github.com/grayhemp/bats-mock
  #
  # Prepare directory with mock binaries, get it's path, and export it so that
  # bats-mock could use it internally.
  BATS_MOCK_TMPDIR="$(mock_prepare_tmp)"
  export "BATS_MOCK_TMPDIR"
  # Set the path to temp mocked binaries directory as the first location in
  # PATH to lookup in mock directories first. This change lives only for the
  # duration of the test and will be reset after. It does not modify the PATH
  # outside of the running test.
  PATH="${BATS_MOCK_TMPDIR}:$PATH"
}

# Prepare temporary mock directory.
mock_prepare_tmp() {
  rm -rf "${BATS_TMPDIR}/bats-mock-tmp" >/dev/null
  mkdir -p "${BATS_TMPDIR}/bats-mock-tmp"
  echo "${BATS_TMPDIR}/bats-mock-tmp"
}

# Mock provided command.
# Arguments:
#  1. Mocked command name,
# Outputs:
#   STDOUT: path to created mock file.
mock_command() {
  mocked_command="${1}"
  mock="$(mock_create)"
  mock_path="${mock%/*}"
  mock_file="${mock##*/}"
  ln -sf "${mock_path}/${mock_file}" "${mock_path}/${mocked_command}"
  echo "$mock"
}
