#!/usr/bin/env bash
##
# Run custom Lagoon task.
#
# @see https://github.com/amazeeio/lagoon-cli
#
# shellcheck disable=SC1090,SC1091

t=$(mktemp) && export -p >"${t}" && set -a && . ./.env && if [ -f ./.env.local ]; then . ./.env.local; fi && set +a && . "${t}" && rm "${t}" && unset t

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# The task name.
DREVOPS_TASK_LAGOON_NAME="${DREVOPS_TASK_LAGOON_NAME:-Automation task}"

# The Lagoon project to run tasks for.
DREVOPS_TASK_LAGOON_PROJECT="${DREVOPS_TASK_LAGOON_PROJECT:-${LAGOON_PROJECT:-}}"

# The Lagoon branch to run the task on.
DREVOPS_TASK_LAGOON_BRANCH="${DREVOPS_TASK_LAGOON_BRANCH:-}"

# The task command to execute.
DREVOPS_TASK_LAGOON_COMMAND="${DREVOPS_TASK_LAGOON_COMMAND:-}"

# The Lagoon instance name to interact with.
DREVOPS_TASK_LAGOON_INSTANCE="${DREVOPS_TASK_LAGOON_INSTANCE:-amazeeio}"

# The Lagoon instance GraphQL endpoint to interact with.
DREVOPS_TASK_LAGOON_INSTANCE_GRAPHQL="${DREVOPS_TASK_LAGOON_INSTANCE_GRAPHQL:-https://api.lagoon.amazeeio.cloud/graphql}"

# The Lagoon instance hostname to interact with.
DREVOPS_TASK_LAGOON_INSTANCE_HOSTNAME="${DREVOPS_TASK_LAGOON_INSTANCE_HOSTNAME:-ssh.lagoon.amazeeio.cloud}"

# The Lagoon instance port to interact with.
DREVOPS_TASK_LAGOON_INSTANCE_PORT="${DREVOPS_TASK_LAGOON_INSTANCE_PORT:-32222}"

# SSH key fingerprint used to connect to remote.
##
# If not used, the currently loaded default SSH key (the key used for code
# checkout) will be used or deployment will fail with an error if the default
# SSH key is not loaded.
# In most cases, the default SSH key does not work (because it is a read-only
# key used by CircleCI to checkout code from git), so you should add another
# deployment key.
DREVOPS_TASK_SSH_FINGERPRINT="${DREVOPS_TASK_SSH_FINGERPRINT:-}"

# Default SSH file used if custom fingerprint is not provided.
DREVOPS_TASK_SSH_FILE="${DREVOPS_TASK_SSH_FILE:-${HOME}/.ssh/id_rsa}"

# Location of the Lagoon CLI binary.
DREVOPS_TASK_LAGOON_BIN_PATH="${DREVOPS_TASK_LAGOON_BIN_PATH:-/tmp}"

# Flag to force the installation of Lagoon CLI.
DREVOPS_TASK_LAGOON_INSTALL_CLI_FORCE="${DREVOPS_TASK_LAGOON_INSTALL_CLI_FORCE:-}"

# Lagoon CLI version to use.
DREVOPS_TASK_LAGOON_LAGOONCLI_VERSION="${DREVOPS_TASK_LAGOON_LAGOONCLI_VERSION:-latest}"

# ------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

info "Started Lagoon task ${DREVOPS_TASK_LAGOON_NAME}."

## Check all required values.
[ -z "${DREVOPS_TASK_LAGOON_NAME}" ] && echo "Missing required value for DREVOPS_TASK_LAGOON_NAME." && exit 1
[ -z "${DREVOPS_TASK_LAGOON_BRANCH}" ] && echo "Missing required value for DREVOPS_TASK_LAGOON_BRANCH." && exit 1
[ -z "${DREVOPS_TASK_LAGOON_COMMAND}" ] && echo "Missing required value for DREVOPS_TASK_LAGOON_COMMAND." && exit 1
[ -z "${DREVOPS_TASK_LAGOON_PROJECT}" ] && echo "Missing required value for DREVOPS_TASK_LAGOON_PROJECT." && exit 1

# Use custom key if fingerprint is provided.
if [ -n "${DREVOPS_TASK_SSH_FINGERPRINT:-}" ]; then
  note "Custom task key is provided."
  DREVOPS_TASK_SSH_FILE="${DREVOPS_TASK_SSH_FINGERPRINT//:/}"
  DREVOPS_TASK_SSH_FILE="${HOME}/.ssh/id_rsa_${DREVOPS_TASK_SSH_FILE//\"/}"
fi

[ ! -f "${DREVOPS_TASK_SSH_FILE:-}" ] && fail "SSH key file ${DREVOPS_TASK_SSH_FILE} does not exist." && exit 1

if ssh-add -l | grep -q "${DREVOPS_TASK_SSH_FILE}"; then
  note "SSH agent has ${DREVOPS_TASK_SSH_FILE} key loaded."
else
  note "SSH agent does not have default key loaded. Trying to load."
  # Remove all other keys and add SSH key from provided fingerprint into SSH agent.
  ssh-add -D >/dev/null
  ssh-add "${DREVOPS_TASK_SSH_FILE}"
fi

# Disable strict host key checking in CI.
[ -n "${CI:-}" ] && mkdir -p "${HOME}/.ssh/" && echo -e "\nHost *\n\tStrictHostKeyChecking no\n\tUserKnownHostsFile /dev/null\n" >>"${HOME}/.ssh/config"

if ! command -v lagoon >/dev/null || [ -n "${DREVOPS_TASK_LAGOON_INSTALL_CLI_FORCE}" ]; then
  note "Installing Lagoon CLI."

  lagooncli_download_url="https://api.github.com/repos/uselagoon/lagoon-cli/releases/latest"
  if [ "${DREVOPS_TASK_LAGOON_LAGOONCLI_VERSION}" != "latest" ]; then
    lagooncli_download_url="https://api.github.com/repos/uselagoon/lagoon-cli/releases/tags/${DREVOPS_TASK_LAGOON_LAGOONCLI_VERSION}"
  fi

  curl -sL "${lagooncli_download_url}" |
    grep "browser_download_url" |
    grep -i "$(uname -s)-amd64\"$" |
    cut -d '"' -f 4 |
    xargs curl -L -o "${DREVOPS_TASK_LAGOON_BIN_PATH}/lagoon"
  chmod +x "${DREVOPS_TASK_LAGOON_BIN_PATH}/lagoon"
  export PATH="${PATH}:${DREVOPS_TASK_LAGOON_BIN_PATH}"
fi

note "Configuring Lagoon instance."
lagoon config add --force -l "${DREVOPS_TASK_LAGOON_INSTANCE}" -g "${DREVOPS_TASK_LAGOON_INSTANCE_GRAPHQL}" -H "${DREVOPS_TASK_LAGOON_INSTANCE_HOSTNAME}" -P "${DREVOPS_TASK_LAGOON_INSTANCE_PORT}"

note "Creating ${DREVOPS_TASK_LAGOON_NAME} task: project ${DREVOPS_TASK_LAGOON_PROJECT}, branch: ${DREVOPS_TASK_LAGOON_BRANCH}."
lagoon --force --skip-update-check -i "${DREVOPS_TASK_SSH_FILE}" -l "${DREVOPS_TASK_LAGOON_INSTANCE}" run custom -p "${DREVOPS_TASK_LAGOON_PROJECT}" -e "${DREVOPS_TASK_LAGOON_BRANCH}" -N "${DREVOPS_TASK_LAGOON_NAME}" -c "${DREVOPS_TASK_LAGOON_COMMAND}"

pass "Finished Lagoon task ${DREVOPS_TASK_LAGOON_NAME}."
