#!/bin/sh
set -e

# Post-rollout task: Notify about pre-deployment.
# Generated from .lagoon.yml

if [ -n "${LAGOON_PR_NUMBER:-}" ]; then
  export DREVOPS_NOTIFY_REF="$LAGOON_PR_NUMBER"
  export DREVOPS_NOTIFY_SHA="${LAGOON_PR_HEAD_SHA#origin/}"
  export DREVOPS_NOTIFY_BRANCH="$LAGOON_PR_HEAD_BRANCH"
else
  branch="${QUANT_ENV_NAME:-${LAGOON_GIT_BRANCH:-unknown}}"
  GIT_SHA="${LAGOON_GIT_SHA:-$branch}"
  export DREVOPS_NOTIFY_REF="$branch"
  export DREVOPS_NOTIFY_SHA="$GIT_SHA"
  export DREVOPS_NOTIFY_BRANCH="$branch"
fi

# Project name and environment URL (Quant or Lagoon)
PROJECT_NAME="${QUANT_APP_NAME:-${LAGOON_PROJECT:-unknown}}"
ENV_URL="${QUANT_ROUTE:-${LAGOON_ROUTE:-unknown}}"

DREVOPS_NOTIFY_PROJECT="$PROJECT_NAME" \
DREVOPS_NOTIFY_ENVIRONMENT_URL="$ENV_URL" \
DREVOPS_NOTIFY_EVENT=pre_deployment \
./scripts/drevops/notify.sh || true
