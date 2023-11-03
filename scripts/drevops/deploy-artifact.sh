#!/usr/bin/env bash
##
# Deploy code via pushing an artifact to a remote git repository.
#
# @see https://github.com/drevops/git-artifact
#
# Deployment to remote git repositories allows to build the project code as
# an artifact in CI and then commit only required files to the destination
# repository.
#
# During deployment, the `.gitignore.deployment` file determines which files
# to exclude, using the `.gitignore` syntax. When preparing the artifact build,
# this file supersedes the existing `.gitignore`, and any specified files are
# excluded.
#
# IMPORTANT! This script runs outside the container on the host system.
#
# shellcheck disable=SC1090,SC1091

t=$(mktemp) && export -p >"${t}" && set -a && . ./.env && if [ -f ./.env.local ]; then . ./.env.local; fi && set +a && . "${t}" && rm "${t}" && unset t

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# Remote repository to push code to.
DREVOPS_DEPLOY_ARTIFACT_GIT_REMOTE="${DREVOPS_DEPLOY_ARTIFACT_GIT_REMOTE:-}"

# Email address of the user who will be committing to a remote repository.
DREVOPS_DEPLOY_ARTIFACT_GIT_USER_NAME="${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_NAME:-"Deployment Robot"}"

# Name of the user who will be committing to a remote repository.
DREVOPS_DEPLOY_ARTIFACT_GIT_USER_EMAIL="${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_EMAIL:-}"

# Source of the code to be used for artifact building.
DREVOPS_DEPLOY_ARTIFACT_SRC="${DREVOPS_DEPLOY_ARTIFACT_SRC:-}"

# The root directory where the deployment script should run from. Defaults to
# the current directory.
DREVOPS_DEPLOY_ARTIFACT_ROOT="${DREVOPS_DEPLOY_ARTIFACT_ROOT:-$(pwd)}"

# Remote repository branch. Can be a specific branch or a token.
# @see https://github.com/drevops/git-artifact#token-support
DREVOPS_DEPLOY_ARTIFACT_DST_BRANCH="${DREVOPS_DEPLOY_ARTIFACT_DST_BRANCH:-[branch]}"

# Deployment report file name.
DREVOPS_DEPLOY_ARTIFACT_REPORT_FILE="${DREVOPS_DEPLOY_ARTIFACT_REPORT_FILE:-${DREVOPS_DEPLOY_ARTIFACT_ROOT}/deployment_report.txt}"

# SSH key fingerprint used to connect to remote.
DREVOPS_DEPLOY_SSH_FINGERPRINT="${DREVOPS_DEPLOY_SSH_FINGERPRINT:-}"

# Default SSH file used if custom fingerprint is not provided.
DREVOPS_DEPLOY_SSH_FILE="${DREVOPS_DEPLOY_SSH_FILE:-${HOME}/.ssh/id_rsa}"

# ------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

info "Started ARTIFACT deployment."

# Check all required values.
[ -z "${DREVOPS_DEPLOY_ARTIFACT_GIT_REMOTE}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_GIT_REMOTE." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_DST_BRANCH}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_DST_BRANCH." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_SRC}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_SRC." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_ROOT}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_ROOT." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_REPORT_FILE}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_REPORT_FILE." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_NAME}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_GIT_USER_NAME." && exit 1
[ -z "${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_EMAIL}" ] && echo "Missing required value for DREVOPS_DEPLOY_ARTIFACT_GIT_USER_EMAIL." && exit 1

# Configure global git settings, if they do not exist.
[ "$(git config --global user.name)" = "" ] && note "Configuring global git user name." && git config --global user.name "${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_NAME}"
[ "$(git config --global user.email)" = "" ] && note "Configuring global git user email." && git config --global user.email "${DREVOPS_DEPLOY_ARTIFACT_GIT_USER_EMAIL}"

# Use custom deploy key if fingerprint is provided.
if [ -n "${DREVOPS_DEPLOY_SSH_FINGERPRINT:-}" ]; then
  note "Custom deployment key is provided."
  DREVOPS_DEPLOY_SSH_FILE="${DREVOPS_DEPLOY_SSH_FINGERPRINT//:/}"
  DREVOPS_DEPLOY_SSH_FILE="${HOME}/.ssh/id_rsa_${DREVOPS_DEPLOY_SSH_FILE//\"/}"
fi

[ ! -f "${DREVOPS_DEPLOY_SSH_FILE:-}" ] && fail "SSH key file ${DREVOPS_DEPLOY_SSH_FILE} does not exist." && exit 1

if ssh-add -l | grep -q "${DREVOPS_DEPLOY_SSH_FILE}"; then
  note "SSH agent has ${DREVOPS_DEPLOY_SSH_FILE} key loaded."
else
  note "SSH agent does not have default key loaded. Trying to load."
  # Remove all other keys and add SSH key from provided fingerprint into SSH agent.
  ssh-add -D >/dev/null
  ssh-add "${DREVOPS_DEPLOY_SSH_FILE}"
fi

# Disable strict host key checking in CI.
[ -n "${CI:-}" ] && mkdir -p "${HOME}/.ssh/" && echo -e "\nHost *\n\tStrictHostKeyChecking no\n\tUserKnownHostsFile /dev/null\n" >>"${HOME}/.ssh/config"

note "Installing artifact builder."
composer global require --dev -n --ansi --prefer-source --ignore-platform-reqs drevops/git-artifact:^0.5

# Copying git repo files meta file to the deploy code repo as it may not exist
# in deploy code source files.
cp -a "${DREVOPS_DEPLOY_ARTIFACT_ROOT}"/.git "${DREVOPS_DEPLOY_ARTIFACT_SRC}" || true
# Copying deployment .gitignore as it may not exist in deploy code source files.
cp -a "${DREVOPS_DEPLOY_ARTIFACT_ROOT}"/.gitignore.deployment "${DREVOPS_DEPLOY_ARTIFACT_SRC}" || true

note "Running artifact builder."
# Add --debug to debug any deployment issues.
"${HOME}/.composer/vendor/bin/robo" --ansi \
  --load-from "${HOME}/.composer/vendor/drevops/git-artifact/RoboFile.php" artifact "${DREVOPS_DEPLOY_ARTIFACT_GIT_REMOTE}" \
  --root="${DREVOPS_DEPLOY_ARTIFACT_ROOT}" \
  --src="${DREVOPS_DEPLOY_ARTIFACT_SRC}" \
  --branch="${DREVOPS_DEPLOY_ARTIFACT_DST_BRANCH}" \
  --gitignore="${DREVOPS_DEPLOY_ARTIFACT_SRC}"/.gitignore.deployment \
  --report="${DREVOPS_DEPLOY_ARTIFACT_REPORT_FILE}" \
  --push

pass "Finished ARTIFACT deployment."
