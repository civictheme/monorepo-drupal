#!/usr/bin/env bash
##
# Deploy visual regression comparison report to Netlify.
#
# Appends VR_NETLIFY_URL to /tmp/vr-stats.env for use by vr-post-pr-comment.sh.
# Requires NETLIFY_AUTH_TOKEN and NETLIFY_SITE_ID environment variables.
#

set -euo pipefail

STATS_FILE="/tmp/vr-stats.env"
REPORT_DIR="/tmp/vr-report/baseline--current"

if [ "${CIRCLE_BRANCH}" = "develop" ]; then
  echo "On develop branch. Skipping Netlify deploy."
  exit 0
fi

if [ ! -d "${REPORT_DIR}" ]; then
  echo "No comparison report found at ${REPORT_DIR}. Skipping Netlify deploy."
  exit 0
fi

echo "Contents of ${REPORT_DIR}:"
ls -lR "${REPORT_DIR}"

if [ -z "${NETLIFY_AUTH_TOKEN:-}" ] || [ -z "${NETLIFY_SITE_ID:-}" ]; then
  echo "NETLIFY_AUTH_TOKEN or NETLIFY_SITE_ID not set. Skipping Netlify deploy."
  exit 0
fi

echo "Deploying to Netlify..."
DEPLOY_OUTPUT=$(npx netlify-cli deploy \
  --dir="${REPORT_DIR}" \
  --site="${NETLIFY_SITE_ID}" \
  --auth="${NETLIFY_AUTH_TOKEN}" \
  --message="VR report: ${CIRCLE_BRANCH} (build ${CIRCLE_BUILD_NUM})" \
  --json 2>&1) || true

echo "Netlify response:"
echo "${DEPLOY_OUTPUT}"

DEPLOY_URL=""
if echo "${DEPLOY_OUTPUT}" > /tmp/netlify-response.json 2>/dev/null; then
  DEPLOY_URL=$(sed -n 's/.*"deploy_url": *"\([^"]*\)".*/\1/p' /tmp/netlify-response.json) || true
fi

if [ -n "${DEPLOY_URL}" ]; then
  echo "export VR_NETLIFY_URL='${DEPLOY_URL}'" >> "${STATS_FILE}"
  echo "Deployed VR report to ${DEPLOY_URL}"
else
  echo "Netlify deploy did not return a URL. Falling back to CircleCI artifacts."
fi
