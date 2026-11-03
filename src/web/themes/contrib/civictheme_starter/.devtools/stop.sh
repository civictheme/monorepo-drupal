#!/usr/bin/env bash
##
# Stop development environment.
#
# shellcheck disable=SC2015,SC2094,SC2002

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

#-------------------------------------------------------------------------------

echo "==============================="
echo "      💻 STOP ENVIRONMENT      "
echo "==============================="
echo

info "Stopping previously started services, if any."
killall -9 php >/dev/null 2>&1 || true
sleep 1
pass "Services stopped."

echo
echo "==============================="
echo "   💻 ENVIRONMENT STOPPED ✅  "
echo "==============================="
echo
