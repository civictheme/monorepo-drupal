#!/usr/bin/env bash
##
# Post or update a visual regression results comment on the GitHub PR.
#
# Expects /tmp/vr-stats.env to have been written by vr-extract-stats.sh and
# optionally vr-deploy-netlify.sh (for VR_NETLIFY_URL).
# Requires GITHUB_TOKEN, CIRCLE_PROJECT_USERNAME, CIRCLE_PROJECT_REPONAME,
# CIRCLE_BRANCH, and CIRCLE_WORKFLOW_JOB_ID environment variables.
#

set -euo pipefail

source /tmp/vr-stats.env

REPO="${CIRCLE_PROJECT_USERNAME}/${CIRCLE_PROJECT_REPONAME}"
COMMENT_MARKER="<!-- vr-drupal-comment -->"

# Find the PR number for this branch.
PR_NUMBER=$(curl -s -H "Authorization: token ${GITHUB_TOKEN}" \
  "https://api.github.com/repos/${REPO}/pulls?head=${CIRCLE_PROJECT_USERNAME}:${CIRCLE_BRANCH}&state=open" \
  | node -e "const d=require('fs').readFileSync('/dev/stdin','utf8'); const pr=JSON.parse(d); console.log(pr[0]?.number || '')")

if [ -z "${PR_NUMBER}" ]; then
  echo "No open PR found for branch ${CIRCLE_BRANCH}. Skipping comment."
  exit 0
fi

# Build report URL — prefer Netlify, fall back to CircleCI artifact.
if [ -n "${VR_NETLIFY_URL:-}" ]; then
  REPORT_URL="${VR_NETLIFY_URL}"
  REPORT_SOURCE="Netlify"
else
  ARTIFACT_BASE="https://output.circle-artifacts.com/output/job/${CIRCLE_WORKFLOW_JOB_ID}/artifacts/0"
  REPORT_URL="${ARTIFACT_BASE}/visual-regression/report/baseline--current/index.html"
  REPORT_SOURCE="CircleCI artifact"
fi

# Build comment body.
if [ "${VR_HAS_RESULTS}" = "true" ]; then
  if [ "${VR_FAILED}" -gt 0 ] || [ "${VR_NEW}" -gt 0 ] || [ "${VR_DELETED}" -gt 0 ]; then
    STATUS_ICON=":warning:"
    STATUS_TEXT="Visual changes detected"
  else
    STATUS_ICON=":white_check_mark:"
    STATUS_TEXT="No visual changes"
  fi

  BODY="${STATUS_ICON} **Visual Regression Results**

| Metric | Count |
|--------|-------|
| Total | ${VR_TOTAL} |
| Passed | ${VR_PASSED} |
| Changed | ${VR_FAILED} |
| New | ${VR_NEW} |
| Deleted | ${VR_DELETED} |

**Status:** ${STATUS_TEXT}

[View full comparison report](${REPORT_URL}) (${REPORT_SOURCE})

${COMMENT_MARKER}"
else
  BODY=":information_source: **Visual Regression** — No baseline found for \`develop\`. Comparison skipped.

${COMMENT_MARKER}"
fi

# Check for existing comment to update.
COMMENT_ID=$(curl -s -H "Authorization: token ${GITHUB_TOKEN}" \
  "https://api.github.com/repos/${REPO}/issues/${PR_NUMBER}/comments" \
  | node -e "
    const d=require('fs').readFileSync('/dev/stdin','utf8');
    const comments=JSON.parse(d);
    const c=comments.find(c => c.body.includes('${COMMENT_MARKER}'));
    console.log(c?.id || '');
  ")

PAYLOAD=$(node -e "console.log(JSON.stringify({body: process.argv[1]}))" "${BODY}")

if [ -n "${COMMENT_ID}" ]; then
  curl -s -X PATCH \
    -H "Authorization: token ${GITHUB_TOKEN}" \
    -H "Content-Type: application/json" \
    -d "${PAYLOAD}" \
    "https://api.github.com/repos/${REPO}/issues/comments/${COMMENT_ID}"
  echo "Updated existing PR comment ${COMMENT_ID}."
else
  curl -s -X POST \
    -H "Authorization: token ${GITHUB_TOKEN}" \
    -H "Content-Type: application/json" \
    -d "${PAYLOAD}" \
    "https://api.github.com/repos/${REPO}/issues/${PR_NUMBER}/comments"
  echo "Posted new PR comment."
fi
