#!/usr/bin/env bash
##
# Compare current screenshots against the cached baseline.
#
# Host-mode: vr-drupal, the screenshot sets and the report all live on the CI
# runner (the scaffold app container has no node). Registers the restored
# baseline in project.json, runs the vr-drupal comparison, and copies the
# report to /tmp/vr-report.
#
# Skips on develop (develop IS the baseline) or when no baseline cache exists.
#

set -euo pipefail

VR_BRANCH="${VR_BRANCH:-${GITHUB_REF_NAME:-}}"
VR_PROJECT_DIR=".visual-regression/civictheme-ci"

if [ "${VR_BRANCH}" = "develop" ] || [ ! -d "/tmp/vr-baseline/baseline" ]; then
  echo "No baseline available or on develop branch. Skipping comparison."
  exit 0
fi

# Place the baseline set next to the current one.
mkdir -p "${VR_PROJECT_DIR}/screenshot-sets/sets"
rm -rf "${VR_PROJECT_DIR}/screenshot-sets/sets/baseline"
cp -r /tmp/vr-baseline/baseline "${VR_PROJECT_DIR}/screenshot-sets/sets/baseline"

# Register baseline snapshot in project.json.
node -e "
  const fs = require('fs');
  const f = '${VR_PROJECT_DIR}/project.json';
  const p = JSON.parse(fs.readFileSync(f));
  p.snapshots = p.snapshots || {};
  p.snapshots.baseline = { directory: 'screenshot-sets/sets/baseline', date: new Date().toISOString(), count: 0 };
  fs.writeFileSync(f, JSON.stringify(p, null, 2));
"

# Run comparison.
vr-drupal compare civictheme-ci --source baseline --target current --no-interactive --aggregate-screenshots || true

# Copy comparison report out.
mkdir -p /tmp/vr-report
cp -r "${VR_PROJECT_DIR}/screenshot-sets/comparisons/." /tmp/vr-report/ || true
