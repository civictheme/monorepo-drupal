#!/usr/bin/env bash
##
# Deploy code to a remote repository.
#
# - configures local git
# - adds deployment SSH key to SSH agent
# - force-pushes code to a remote code repository branch
#
# It is a good practice to create a separate Deployer user with own SSH key for
# every project.
#
# Add the following variables through CircleCI UI.
# - DEPLOY_USER_NAME - name of the user who will be committing to a remote repository.
# - DEPLOY_USER_EMAIL - email address of the user who will be committing to a remote repository.
# - DEPLOY_REMOTE - remote repository to push code to.
# - DEPLOY_PROCEED - set to 1 if the deployment should proceed. Useful for testing CI configuration before an actual code push.

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Name of the user who will be committing to a remote repository.
DEPLOY_USER_NAME="${DEPLOY_USER_NAME:-}"

# Email address of the user who will be committing to a remote repository.
DEPLOY_USER_EMAIL="${DEPLOY_USER_EMAIL:-}"

# Remote repository to push code to.
DEPLOY_REMOTE="${DEPLOY_REMOTE:-}"

# Git branch to deploy. If not provided - current branch will be used.
DEPLOY_BRANCH="${DEPLOY_BRANCH:-}"

# The fingerprint of the SSH key of the user on behalf of which the deployment
# is performed.
DEPLOY_SSH_FINGERPRINT="${DEPLOY_SSH_FINGERPRINT:-}"

# Set to 1 if the deployment should proceed. Useful for testing CI configuration
# before an actual code push.
DEPLOY_PROCEED="${DEPLOY_PROCEED:-0}"

#-------------------------------------------------------------------------------

[ -z "${DEPLOY_USER_NAME}" ] && echo "ERROR: Missing required value for DEPLOY_USER_NAME" && exit 1
[ -z "${DEPLOY_USER_EMAIL}" ] && echo "ERROR: Missing required value for DEPLOY_USER_EMAIL" && exit 1
[ -z "${DEPLOY_REMOTE}" ] && echo "ERROR: Missing required value for DEPLOY_REMOTE" && exit 1
[ -z "${DEPLOY_SSH_FINGERPRINT}" ] && echo "ERROR: Missing required value for DEPLOY_SSH_FINGERPRINT" && exit 1

[ "${DEPLOY_PROCEED}" != "1" ] && echo "==> Skipping deployment because \$DEPLOY_PROCEED is not set to 1" && exit 0

# Configure git and SSH to connect to remote servers for deployment.
mkdir -p "${HOME}/.ssh/"
echo -e "Host *\n\tStrictHostKeyChecking no\n" > "${HOME}/.ssh/config"
DEPLOY_SSH_FILE="${DEPLOY_SSH_FINGERPRINT//:}"
DEPLOY_SSH_FILE="${HOME}/.ssh/id_rsa_${DEPLOY_SSH_FILE//\"}"
[ ! -f "${DEPLOY_SSH_FILE:-}" ] && echo "ERROR: Unable to find Deploy SSH key file ${DEPLOY_SSH_FILE}." && exit 1
if [ -z "${SSH_AGENT_PID:-}" ]; then eval "$(ssh-agent)"; fi
ssh-add -D > /dev/null
ssh-add "${DEPLOY_SSH_FILE}"

# Configure git user name and email, but only if not already set.
[ "$(git config --global user.name)" == "" ] && echo "==> Configuring global git user name ${DEPLOY_USER_NAME}." && git config --global user.name "${DEPLOY_USER_NAME}"
[ "$(git config --global user.email)" == "" ] && echo "==> Configuring global git user email ${DEPLOY_USER_EMAIL}." && git config --global user.email "${DEPLOY_USER_EMAIL}"

# Set git to push to a matching remote branch.
git config --global push.default matching

echo "==> Adding remote ${DEPLOY_REMOTE}."
git remote add deployremote "${DEPLOY_REMOTE}"

echo "==> Deploying to remote ${DEPLOY_REMOTE}."

echo "  > Pushing code to branch ${DEPLOY_BRANCH}."
git push --force deployremote HEAD:"${DEPLOY_BRANCH}"

echo "  > Pushing tags."
git push --force --tags deployremote || true

echo
echo "==> Deployment finished."
echo
