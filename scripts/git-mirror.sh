#!/usr/bin/env bash
##
# Mirror git repo branch.
#
set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

GIT_MIRROR_BRANCH="${GIT_MIRROR_BRANCH:-$1}"
GIT_MIRROR_BRANCH_DST="${GIT_MIRROR_BRANCH_DST:-${GIT_MIRROR_BRANCH}}"
GIT_MIRROR_REMOTE="${GIT_MIRROR_REMOTE:-origin}"

# Flag to push the branch.
GIT_MIRROR_PUSH="${GIT_MIRROR_PUSH:-}"

# ------------------------------------------------------------------------------

echo "==> Started code mirroring."

# Check all required values.
[ -z "${GIT_MIRROR_BRANCH}" ] && echo "Missing required value for GIT_MIRROR_BRANCH." && exit 1
[ -z "${GIT_MIRROR_BRANCH_DST}" ] && echo "Missing required value for GIT_MIRROR_BRANCH_REMOTE." && exit 1
[ -z "${GIT_MIRROR_REMOTE}" ] && echo "Missing required value for GIT_MIRROR_BRANCH." && exit 1
[ -z "${GIT_MIRROR_USER_NAME}" ] && echo "Missing required value for GIT_MIRROR_USER_NAME." && exit 1
[ -z "${GIT_MIRROR_USER_EMAIL}" ] && echo "Missing required value for GIT_MIRROR_USER_EMAIL." && exit 1

# Configure global git settings, if they do not exist.
[ "$(git config --global user.name)" == "" ] && echo "==> Configuring global git user name." && git config --global user.name "${GIT_MIRROR_USER_NAME}"
[ "$(git config --global user.email)" == "" ] && echo "==> Configuring global git user email." && git config --global user.email "${GIT_MIRROR_USER_EMAIL}"

# Use custom deploy key if fingerprint is provided.
if [ -n "${GIT_MIRROR_SSH_FINGERPRINT}" ]; then
  echo "==> Custom deployment key is provided."
  GIT_MIRROR_SSH_FILE="${GIT_MIRROR_SSH_FINGERPRINT//:}"
  GIT_MIRROR_SSH_FILE="${HOME}/.ssh/id_rsa_${GIT_MIRROR_SSH_FILE//\"}"
fi

[ ! -f "${GIT_MIRROR_SSH_FILE}" ] && echo "ERROR: SSH key file ${GIT_MIRROR_SSH_FILE} does not exist." && exit 1

if ssh-add -l | grep -q "${GIT_MIRROR_SSH_FILE}"; then
  echo "==> SSH agent has ${GIT_MIRROR_SSH_FILE} key loaded."
else
  echo "==> SSH agent does not have default key loaded. Trying to load."
  # Remove all other keys and add SSH key from provided fingerprint into SSH agent.
  ssh-add -D > /dev/null
  ssh-add "${GIT_MIRROR_SSH_FILE}"
fi

# Create a temp directory to copy source repository into to prevent changes to source.
SRC_TMPDIR=$(mktemp -d)

echo "==> Copying files from the source repository to ${SRC_TMPDIR}."
rsync -a --keep-dirlinks ./. "${SRC_TMPDIR}"
[ -n "${DREVOPS_DEBUG}" ] && tree -L 4 "${SRC_TMPDIR}"

# Move to the temp source repo directory.
pushd "${SRC_TMPDIR}" >/dev/null || exit 1

# Reset any changes that may have been introduced during the CI run.
git reset --hard

# Checkout the branch, but only if the current branch is not the same.
current_branch="$(git rev-parse --abbrev-ref HEAD)"
if [ "${GIT_MIRROR_BRANCH}" != "${current_branch}" ] ;then
  git checkout -b "${GIT_MIRROR_BRANCH}" "${GIT_MIRROR_REMOTE}/${GIT_MIRROR_BRANCH}" || git switch "${GIT_MIRROR_BRANCH}" || true
fi

if [ "${GIT_MIRROR_PUSH}" == "1" ]; then
  git push "${GIT_MIRROR_REMOTE}" "${GIT_MIRROR_BRANCH}:${GIT_MIRROR_BRANCH_DST}" --force
else
  echo "Would push to ${GIT_MIRROR_BRANCH}"
fi

popd >/dev/null || exit 1

echo "==> Finished code mirroring."
