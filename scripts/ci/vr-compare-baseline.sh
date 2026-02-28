#!/usr/bin/env bash
##
# Compare current screenshots against the cached baseline.
#
# Copies the baseline into the CLI container, registers it in project.json,
# runs the vr-drupal comparison, and copies the report back out.
#
# Skips on develop branch or when no baseline cache exists.
#

set -euo pipefail

if [ "${CIRCLE_BRANCH}" = "develop" ] || [ ! -d "/tmp/vr-baseline/baseline" ]; then
  echo "No baseline available or on develop branch. Skipping comparison."
  exit 0
fi

# Copy baseline into container.
docker compose cp /tmp/vr-baseline/baseline cli:/app/.visual-regression/civictheme-ci/screenshot-sets/sets/baseline

# Register baseline snapshot in project.json.
docker compose exec -T cli sh -c "cd /app && node -e \"
  const fs = require('fs');
  const p = JSON.parse(fs.readFileSync('.visual-regression/civictheme-ci/project.json'));
  p.snapshots = p.snapshots || {};
  p.snapshots.baseline = { directory: 'screenshot-sets/sets/baseline', date: new Date().toISOString(), count: 0 };
  fs.writeFileSync('.visual-regression/civictheme-ci/project.json', JSON.stringify(p, null, 2));
\""

# Run comparison.
docker compose exec -T \
  -e PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
  -e PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser \
  cli vr-drupal compare civictheme-ci --source baseline --target current --no-interactive --aggregate-screenshots || true

# Copy comparison report out.
mkdir -p /tmp/vr-report
docker compose cp cli:/app/.visual-regression/civictheme-ci/screenshot-sets/comparisons/. /tmp/vr-report/ || true
