#!/usr/bin/env bash
##
# Reset project to a freshly cloned repository state.
#
# shellcheck disable=SC1090,SC1091

t=$(mktemp) && export -p >"${t}" && set -a && . ./.env && if [ -f ./.env.local ]; then . ./.env.local; fi && set +a && . "${t}" && rm "${t}" && unset t

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# ------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

is_hard_reset="$([ "${1:-}" == "hard" ] && echo "1" || echo "0")"

info "Started reset."

rm -rf \
  "./vendor" \
  "./${DREVOPS_WEBROOT}/core" \
  "./${DREVOPS_WEBROOT}/profiles/contrib" \
  "./${DREVOPS_WEBROOT}/modules/contrib" \
  "./${DREVOPS_WEBROOT}/themes/custom/*/build" \
  "./${DREVOPS_WEBROOT}/themes/custom/*/scss/_components.scss"

# shellcheck disable=SC2038
find . -type d -name node_modules | xargs rm -Rf

if [ "${is_hard_reset}" = "1" ]; then
  note "Changing permissions and remove all other untracked files."

  git ls-files --others -i --exclude-from=.gitignore -z | while IFS= read -r -d '' file; do
    chmod 777 "${file}" >/dev/null || true
    rm -rf "${file}" >/dev/null || true
  done

  note "Resetting repository files."
  git reset --hard

  note "Removing all untracked, files."
  git clean -f -d

  note "Removing empty directories."
  find . -type d -not -path "./.git/*" -empty -delete
fi

pass "Finished reset."
