#!/usr/bin/env bash
##
# Start development environment.
#
# shellcheck disable=SC2015,SC2094,SC2002

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Webserver hostname.
WEBSERVER_HOST="${WEBSERVER_HOST:-localhost}"

# Webserver port.
WEBSERVER_PORT="${WEBSERVER_PORT:-8000}"

# Webserver wait timeout.
WEBSERVER_WAIT_TIMEOUT="${WEBSERVER_WAIT_TIMEOUT:-5}"

#-------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

#-------------------------------------------------------------------------------

echo "==============================="
echo "      💻 START ENVIRONMENT     "
echo "==============================="
echo

info "Stopping previously started services, if any."
killall -9 php >/dev/null 2>&1 || true

info "Starting the PHP webserver."
nohup php -S "${WEBSERVER_HOST}:${WEBSERVER_PORT}" -t "$(pwd)/build/web" "$(pwd)/build/web/.ht.router.php" >/tmp/php.log 2>&1 &

note "Waiting ${WEBSERVER_WAIT_TIMEOUT} seconds for the server to be ready."
sleep "${WEBSERVER_WAIT_TIMEOUT}"

note "Checking that the server was started."
netstat_opts='-tulpn'
[ "$(uname)" == "Darwin" ] && netstat_opts='-anv' || true
netstat "${netstat_opts[@]}" | grep -q "${WEBSERVER_PORT}" || (echo "ERROR: Unable to start inbuilt PHP server" && cat /tmp/php.log && exit 1)

pass "Server started successfully."

info "Checking that the server can serve content."
curl -s -o /dev/null -w "%{http_code}" -L -I "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}" | grep -q 200 || (echo "ERROR: Server is started, but site cannot be served" && exit 1)
pass "Server can serve content."

echo
echo "==============================="
echo "    💻 ENVIRONMENT READY  ✅  "
echo "==============================="
echo
echo "Directory : $(pwd)/build/web"
echo "URL       : http://${WEBSERVER_HOST}:${WEBSERVER_PORT}"
echo
echo "Re-run when you enable or disable XDebug."
echo
echo "> Next steps:"
echo "  .devtools/provision.sh    # Provision the website"
echo
