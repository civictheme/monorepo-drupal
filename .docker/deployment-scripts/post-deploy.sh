#!/bin/bash
# Post-deploy: runs on every Salsa Hosting deploy, in every environment.
#
# Salsa Hosting builds code in GitHub Actions and publishes an image. This runs
# after that image is pulled, so the code build has already happened and all
# that remains is bringing the database up to date.
#
# ONE script for every environment. Development and production used to have
# their own copies, which differed only in a colour and a log line — two files
# guaranteeing that a fix to one silently missed the other. If you need
# environment-specific behaviour, branch on QUANT_ENV_TYPE here:
#
#   if [ "$QUANT_ENV_TYPE" = "production" ]; then
#     "$DRUSH" state:set system.maintenance_mode 1
#   fi
#
# It calls deploy.sh, NOT provision.sh. provision.sh bootstraps — it drops every
# table and installs a site or imports a dump from /import, which is a
# local-only bind mount. Pointing a server at it was both dangerous and broken:
# with no /import present it exited 2 and the deploy failed.
set -euo pipefail

CYAN='\033[0;36m'
NC='\033[0m'

printf "${CYAN}[post-deploy]${NC} environment: %s\n" "${QUANT_ENV_TYPE:-<unset>}"

DEPLOY_SCRIPT="/opt/drupal/scripts/quant/deploy.sh"

if [ ! -x "$DEPLOY_SCRIPT" ]; then
  printf "[post-deploy] ERROR: not found or not executable: %s\n" "$DEPLOY_SCRIPT" >&2
  exit 1
fi

exec "$DEPLOY_SCRIPT"
