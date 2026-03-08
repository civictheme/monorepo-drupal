#!/usr/bin/env bash
##
# Extract visual regression comparison statistics from reg.json.
#
# Writes environment variables to /tmp/vr-stats.env for use by downstream steps.
#

set -euo pipefail

STATS_FILE="/tmp/vr-stats.env"
REG_JSON="/tmp/vr-report/baseline--current/reg.json"

if [ "${CIRCLE_BRANCH}" != "develop" ] && [ -f "${REG_JSON}" ]; then
  PASSED=$(node -e "const r=require('${REG_JSON}'); console.log((r.passedItems||[]).length)")
  FAILED=$(node -e "const r=require('${REG_JSON}'); console.log((r.failedItems||[]).length)")
  NEW=$(node -e "const r=require('${REG_JSON}'); console.log((r.newItems||[]).length)")
  DELETED=$(node -e "const r=require('${REG_JSON}'); console.log((r.deletedItems||[]).length)")
  TOTAL=$((PASSED + FAILED + NEW + DELETED))

  echo "export VR_TOTAL=${TOTAL}" >> "${STATS_FILE}"
  echo "export VR_PASSED=${PASSED}" >> "${STATS_FILE}"
  echo "export VR_FAILED=${FAILED}" >> "${STATS_FILE}"
  echo "export VR_NEW=${NEW}" >> "${STATS_FILE}"
  echo "export VR_DELETED=${DELETED}" >> "${STATS_FILE}"
  echo "export VR_HAS_RESULTS=true" >> "${STATS_FILE}"
else
  echo "export VR_HAS_RESULTS=false" >> "${STATS_FILE}"
fi
