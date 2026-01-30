#!/bin/sh
set -e

# Post-rollout task: Provision site
# Generated from .lagoon.yml

 if [ "$LAGOON_ENVIRONMENT_TYPE" = "production" ] || [ "$LAGOON_GIT_BRANCH" = "${DREVOPS_LAGOON_PRODUCTION_BRANCH:-main}" ]; then
   echo "==> Running in PRODUCTION environment."
   # Never unblock admin user in production.
   export DRUPAL_UNBLOCK_ADMIN=0
   # Never sanitize DB in production.
   export DREVOPS_PROVISION_SANITIZE_DB_SKIP=1
 fi
#
# Deployments from UI are not able to bypass the value of
# DREVOPS_PROVISION_OVERRIDE_DB set by the deploy-lagoon.sh
# during previous deployments (it sets value to '0' to mitigate Lagoon bug
# where environment variables cannot be deleted and have to be set to a value).
# @see https://github.com/uselagoon/lagoon/issues/1922
# Explicitly set DB overwrite flag to the value from .env file for
# deployments from the profile.
 if [ "${DREVOPS_PROVISION_USE_PROFILE}" = "1" ]; then
   export DREVOPS_PROVISION_OVERRIDE_DB="$(cat .env | grep ^DREVOPS_PROVISION_OVERRIDE_DB | cut -c31-)"
 fi
 ./scripts/drevops/provision.sh
