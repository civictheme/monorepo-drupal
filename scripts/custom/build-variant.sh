#!/usr/bin/env bash
##
# Build or re-provision the local stack for one provision variant.
#
# The ahoy build-*/provision-* commands export the variant environment
# (DRUPAL_INSTALL_PROFILE, CIVICTHEME_*, COMPOSER, PHP_VERSION) and call this
# script — one definition of the build flow for every variant, the same shape
# the scaffold's `ahoy build` has. scripts/quant/exec.sh forwards the exported
# variant env into the container, so `composer install`, provision.sh and the
# post-provision hooks all see it.
#
# Usage:
#   scripts/custom/build-variant.sh                    # full: (re)build stack + provision
#   scripts/custom/build-variant.sh --provision-only   # re-provision the running stack
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$HERE"

# ahoy loads .env before running a command body; this script is also the CI
# entrypoint, where ahoy is absent. Fill in anything .env defines that is not
# already in the environment — already-set values (variant exports from the
# ahoy build-* commands, CI env) win over .env defaults.
if [ -f .env ]; then
  while IFS= read -r line; do
    case "$line" in ''|\#*) continue ;; esac
    k="${line%%=*}"
    [[ "$k" =~ ^[A-Za-z_][A-Za-z0-9_]*$ ]] || continue
    if [ -z "${!k+x}" ]; then
      v="${line#*=}"
      v="${v%\"}"; v="${v#\"}"
      export "$k=$v"
    fi
  done <.env
fi

provision_only=0
if [ "${1:-}" = "--provision-only" ]; then
  provision_only=1
fi

if [ "$provision_only" != "1" ]; then
  # See the scaffold's `ahoy up` for why the files dir is pre-created.
  mkdir -p src/web/sites/default/files/private
  scripts/quant/project-name-check.sh
  docker compose up -d --build
  # Step 1 — code build. The override bind-mounts ./src over /opt/drupal, so
  # this repopulates the host tree (web/index.php, vendor/, …). COMPOSER (the
  # govcms manifest swap) is forwarded by exec.sh.
  scripts/quant/build.sh
  # Base theme front-end (components/ + dist/) builds on the HOST — the app
  # container has no node. Must precede provisioning: enabling CivicTheme
  # needs the SDC components/ directory populated.
  scripts/custom/fe-build.sh
fi

# Steps 0, 2, 3 — bootstrap, then deploy (updb + post-provision hooks).
scripts/quant/exec.sh bash -c "/opt/drupal/scripts/quant/provision.sh"

# The civictheme_demo sub-theme is generated at provision time by the
# 20-activate-subtheme hook; its assets build on the host afterwards.
if [ "${CIVICTHEME_SUBTHEME_ACTIVATION_SKIP:-}" != "1" ]; then
  scripts/custom/fe-build.sh --subtheme
fi

scripts/quant/info.sh
