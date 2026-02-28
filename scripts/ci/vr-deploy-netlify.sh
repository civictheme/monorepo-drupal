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

DEPLOY_URL=$(echo "${DEPLOY_OUTPUT}" | node -e "const d=require('fs').readFileSync('/dev/stdin','utf8'); console.log(JSON.parse(d).deploy_ssl_url || '')" 2>/dev/null) || DEPLOY_URL=""

if [ -n "${DEPLOY_URL}" ]; then
  echo "export VR_NETLIFY_URL='${DEPLOY_URL}'" >> "${STATS_FILE}"
  echo "Deployed VR report to ${DEPLOY_URL}"
else
  echo "Netlify deploy did not return a URL. Falling back to CircleCI artifacts."
fi
