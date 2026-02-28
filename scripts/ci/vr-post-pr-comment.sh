#!/usr/bin/env bash
##
# Post or update a visual regression results comment on the GitHub PR.
#
# Expects /tmp/vr-stats.env to have been written by vr-extract-stats.sh and
# optionally vr-deploy-netlify.sh (for VR_NETLIFY_URL).
# Requires GITHUB_CT_PR_COMMENT, CIRCLE_PROJECT_USERNAME, CIRCLE_PROJECT_REPONAME,
# CIRCLE_BRANCH, and CIRCLE_WORKFLOW_JOB_ID environment variables.
#

set -euo pipefail

source /tmp/vr-stats.env

REPO="${CIRCLE_PROJECT_USERNAME}/${CIRCLE_PROJECT_REPONAME}"
COMMENT_MARKER="<!-- vr-drupal-comment -->"

# Find the PR number for this branch.
PR_RESPONSE=$(curl -s -H "Authorization: token ${GITHUB_CT_PR_COMMENT}" \
  "https://api.github.com/repos/${REPO}/pulls?head=${CIRCLE_PROJECT_USERNAME}:${CIRCLE_BRANCH}&state=open") || true

echo "PR lookup response:"
echo "${PR_RESPONSE}"

PR_NUMBER=$(echo "${PR_RESPONSE}" > /tmp/gh-pr-response.json && sed -n 's/.*"number": *\([0-9]*\).*/\1/p' /tmp/gh-pr-response.json | head -1) || true

if [ -z "${PR_NUMBER}" ]; then
  echo "No open PR found for branch ${CIRCLE_BRANCH}. Skipping comment."
  exit 0
fi

echo "Found PR #${PR_NUMBER}"

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
COMMENTS_RESPONSE=$(curl -s -H "Authorization: token ${GITHUB_CT_PR_COMMENT}" \
  "https://api.github.com/repos/${REPO}/issues/${PR_NUMBER}/comments") || true

COMMENT_ID=""
if echo "${COMMENTS_RESPONSE}" > /tmp/gh-comments-response.json 2>/dev/null; then
  COMMENT_ID=$(grep -B5 "${COMMENT_MARKER}" /tmp/gh-comments-response.json | sed -n 's/.*"id": *\([0-9]*\).*/\1/p' | tail -1) || true
fi

# Build JSON payload safely.
PAYLOAD=$(printf '%s' "${BODY}" | python3 -c "import sys,json; print(json.dumps({'body': sys.stdin.read()}))" 2>/dev/null) || true

if [ -z "${PAYLOAD}" ]; then
  echo "Failed to build JSON payload. Skipping comment."
  exit 1
fi

if [ -n "${COMMENT_ID}" ]; then
  echo "Updating existing PR comment ${COMMENT_ID}..."
  RESULT=$(curl -s -X PATCH \
    -H "Authorization: token ${GITHUB_CT_PR_COMMENT}" \
    -H "Content-Type: application/json" \
    -d "${PAYLOAD}" \
    "https://api.github.com/repos/${REPO}/issues/comments/${COMMENT_ID}") || true
else
  echo "Posting new PR comment..."
  RESULT=$(curl -s -X POST \
    -H "Authorization: token ${GITHUB_CT_PR_COMMENT}" \
    -H "Content-Type: application/json" \
    -d "${PAYLOAD}" \
    "https://api.github.com/repos/${REPO}/issues/${PR_NUMBER}/comments") || true
fi

echo "GitHub API response:"
echo "${RESULT}"

# Check for errors in the response.
if echo "${RESULT}" | grep -q '"message"'; then
  echo "Warning: GitHub API returned an error."
else
  echo "Comment posted successfully."
fi
