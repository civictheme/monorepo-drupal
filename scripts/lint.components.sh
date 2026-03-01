#!/usr/bin/env bash
##
# Lint CivicTheme components by visiting pages and checking for HTTP errors.
#
# Reads paths from the visual regression project.json and curls each one.
# Stops at the first HTTP 500+ error, prints the response body, and exits
# with code 1. If all pages return successful responses, exits 0.
#
# This script should be run inside the CLI container via `ahoy cli`.
#
# Environment variables:
#   CIVICTHEME_LINT_COMPONENTS_PROFILE  - Override profile name (optional).
#   CIVICTHEME_LINT_COMPONENTS_BASE_URL - Override base URL (default: http://nginx:8080).
#   CI                                  - Auto-detected; selects civictheme-ci profile.
#   DREVOPS_DEBUG                       - Enables set -x for debug output.

set -eu
[ -n "${DREVOPS_DEBUG:-}" ] && set -x

# Determine profile: explicit override > CI detection > local default.
if [ -n "${CIVICTHEME_LINT_COMPONENTS_PROFILE:-}" ]; then
  PROFILE="${CIVICTHEME_LINT_COMPONENTS_PROFILE}"
elif [ -n "${CI:-}" ]; then
  PROFILE="civictheme-ci"
else
  PROFILE="civictheme-local"
fi

PROJECT_JSON=".visual-regression/${PROFILE}/project.json"

if [ ! -f "${PROJECT_JSON}" ]; then
  echo "ERROR: Cannot find project.json at ${PROJECT_JSON}"
  exit 1
fi

echo "==> Lint components smoke test (profile: ${PROFILE})"

# Base URL: always use internal Docker address since this runs inside the CLI
# container. The project.json base_path is for host-level tools (VR, browser).
BASE_PATH="${CIVICTHEME_LINT_COMPONENTS_BASE_URL:-http://nginx:8080}"

# Extract paths array from project.json.
PATHS="$(node -e "const p=require('./${PROJECT_JSON}'); p['visual-diff']['paths'].forEach(function(v){console.log(v)})")"

if [ -z "${PATHS}" ]; then
  echo "ERROR: No paths found in ${PROJECT_JSON}"
  exit 1
fi

TOTAL=$(echo "${PATHS}" | wc -l)
COUNT=0

while IFS= read -r path; do
  COUNT=$((COUNT + 1))
  URL="${BASE_PATH}${path}"
  printf "[%d/%d] Checking %s ... " "${COUNT}" "${TOTAL}" "${path}"

  HTTP_CODE=$(curl -s -o /tmp/lint-components-response.html -w "%{http_code}" -L "${URL}" 2>/dev/null) || true

  if [ -z "${HTTP_CODE}" ] || [ "${HTTP_CODE}" = "000" ]; then
    echo "ERROR (connection failed)"
    echo ""
    echo "ERROR: Could not connect to ${URL}"
    rm -f /tmp/lint-components-response.html
    exit 1
  fi

  if [ "${HTTP_CODE}" -ge 500 ]; then
    echo "ERROR (HTTP ${HTTP_CODE})"
    echo ""
    echo "--- Response body for ${path} ---"
    cat /tmp/lint-components-response.html
    echo ""
    echo "--- End of response body ---"
    rm -f /tmp/lint-components-response.html
    exit 1
  fi

  echo "OK (${HTTP_CODE})"
done <<< "${PATHS}"

rm -f /tmp/lint-components-response.html
echo ""
echo "  > OK: All ${TOTAL} pages returned successful responses."
