#!/usr/bin/env bash
# Salsa scaffold — deploy steps.
#
# Everything a SERVER does on deploy, and nothing it must not:
#
#   2. database updates   (drush deploy — updb, config:import, cr, deploy:hook)
#   3. post-provision hooks
#
# Salsa Hosting builds code in GitHub Actions and publishes an image; the deploy
# only pulls that image and updates the database. So step 1 (composer, npm) has
# already happened by the time this runs, and step 0 — installing a site or
# importing a dump — must NEVER happen on a server.
#
# That is why this script exists separately from provision.sh. It contains no
# DROP, no site:install and no sql:query --file, so it is safe to run against
# production and safe to run repeatedly. `.scaffold-dev/tests/smoke.sh` asserts
# that property — do not add destructive operations here.
#
# Callers:
#   local   — `ahoy deploy`, and provision.sh after it has bootstrapped
#   cloud   — .docker/deployment-scripts/post-deploy.sh, for every environment
set -euo pipefail

DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"
# MUST match settings.php's config_sync_directory. See provision.sh.
CONFIG_PATH="${DRUPAL_CONFIG_SYNC:-${APP_ROOT}/config/sync}"
HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

log()  { printf '\033[0;36m[deploy]\033[0m %s\n' "$*"; }
die()  { printf '\033[0;31m[deploy]\033[0m %s\n' "$*" >&2; exit 2; }

config_sync_has_files() {
  [ -d "$CONFIG_PATH" ] && find "$CONFIG_PATH" -maxdepth 1 -name '*.yml' -print -quit | grep -q .
}

# `drush deploy` is updb + config:import + cr + deploy:hook. If config/sync is
# empty Drupal refuses the import ("would delete all of your configuration") —
# fatal on a fresh profile install, where the profile already set things up.
# This wrapper picks the right shape per environment.
safe_deploy() {
  if config_sync_has_files; then
    log "Config sync populated ($CONFIG_PATH) — running drush deploy"
    "$DRUSH" deploy -y
  else
    log "Config sync empty ($CONFIG_PATH) — skipping config:import (running updb + cr instead)"
    # No `|| true` on these. They carried it while this code lived in
    # provision.sh and only ever ran locally, where a swallowed failure just
    # meant a re-run. This script now runs on production: a failed database
    # update that reports success is the worst outcome available, because the
    # deploy goes green over a half-migrated database.
    "$DRUSH" updb -y
    "$DRUSH" cr
    "$DRUSH" deploy:hook -y
  fi
}

# A server deploy lands on a site that must already exist. `drush deploy`
# against an uninstalled Drupal fails with a confusing bootstrap error, so say
# what is actually wrong.
"$DRUSH" status --field=bootstrap 2>/dev/null | grep -qi 'success' || die \
  "Drupal is not installed, so there is nothing to deploy to.
  Local: run 'ahoy build' (first time) or 'ahoy provision'.
  Cloud: the environment needs an initial database before deploys can run —
         see docs/deployment.md."

safe_deploy
"$DRUSH" status

# Step 3 — post-provision hooks. Honours SCAFFOLD_POST_PROVISION_ENABLED and
# SCAFFOLD_POST_PROVISION_ON_ERROR; no-op when no hooks are enabled.
if [ -x "${HERE}/run-post-provision.sh" ]; then
  "${HERE}/run-post-provision.sh"
fi
