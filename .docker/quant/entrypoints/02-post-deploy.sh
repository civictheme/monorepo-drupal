#!/bin/bash
# Run the post-deploy steps, unless this is a developer's machine.
#
# QUANT_ENV_TYPE:
#   local          — developer's machine. Bind-mounted src/, no auto-provision;
#                    the user runs `ahoy build` / `ahoy provision` explicitly.
#   anything else  — a server (development, production, a PR environment, …).
#                    Runs post-deploy.sh.
#
# Deliberately "not local" rather than an allow-list of known environments.
# Every server does the same thing — post-deploy.sh is non-destructive and
# idempotent — so there is nothing for a per-environment branch to decide, and
# an unrecognised value should still deploy rather than fail the release. A
# project that does need to vary behaviour branches on QUANT_ENV_TYPE inside
# post-deploy.sh.
set -euo pipefail

if [ "${QUANT_ENV_TYPE:-local}" = "local" ]; then
  echo "QUANT_ENV_TYPE=local — skipping auto post-deploy."
  echo "Run 'ahoy build' (first time) or 'ahoy provision' to bootstrap Drupal."
  exit 0
fi

exec /quant/deployment-scripts/post-deploy.sh
