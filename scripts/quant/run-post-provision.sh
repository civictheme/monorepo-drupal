#!/usr/bin/env bash
# Salsa scaffold — post-provision hook runner.
#
# Invoked by provision.sh after a successful provision (both `profile` and
# `database` modes). Iterates executable `*.sh` files under post-provision.d/
# in lexical order so numeric prefixes (10-, 20-, 30-) control ordering.
#
# Files ending in `.disabled` (or `.sh.disabled`) are ignored — drop the
# suffix AND `chmod +x` to activate a hook. Non-executable `*.sh` files are
# skipped with a notice (so half-written drafts don't blow up provision).
#
# Configuration env vars:
#   SCAFFOLD_POST_PROVISION_ENABLED   1|0          default: 1
#   SCAFFOLD_POST_PROVISION_ON_ERROR  abort|continue   default: abort
#
# Each hook receives the same exports provision.sh has resolved, so a hook
# script doesn't need to re-derive paths or profile context:
#   DRUSH, APP_ROOT, DRUPAL_INSTALL_PROFILE, SCAFFOLD_PROVISION_TYPE,
#   QUANT_ENV_TYPE
#
# Can also be run standalone for re-runs without re-provisioning:
#   ahoy post-provision

set -uo pipefail

ENABLED="${SCAFFOLD_POST_PROVISION_ENABLED:-1}"
ON_ERROR="${SCAFFOLD_POST_PROVISION_ON_ERROR:-abort}"
HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
HOOKS_DIR="${HERE}/post-provision.d"

log()  { printf '\033[0;36m[post-provision]\033[0m %s\n' "$*"; }
warn() { printf '\033[0;33m[post-provision]\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[0;31m[post-provision]\033[0m %s\n' "$*" >&2; exit 2; }

if [ "$ENABLED" != "1" ]; then
  log "disabled via SCAFFOLD_POST_PROVISION_ENABLED=$ENABLED — skipping"
  exit 0
fi

if [ ! -d "$HOOKS_DIR" ]; then
  log "no $HOOKS_DIR directory — nothing to run"
  exit 0
fi

# Hand each hook the resolved context provision.sh built up. Subscripts can
# rely on these without having to repeat the defaulting logic. The
# user-facing names (DRUPAL_INSTALL_PROFILE, SCAFFOLD_PROVISION_TYPE) are
# already in the container environment via docker compose; export them here
# only so direct invocation (`ahoy post-provision`) without a fresh shell
# still works.
export DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
export APP_ROOT="${APP_ROOT:-/opt/drupal}"
export DRUPAL_INSTALL_PROFILE="${DRUPAL_INSTALL_PROFILE:-}"
export SCAFFOLD_PROVISION_TYPE="${SCAFFOLD_PROVISION_TYPE:-}"
export QUANT_ENV_TYPE="${QUANT_ENV_TYPE:-local}"

shopt -s nullglob
hooks=("${HOOKS_DIR}"/*.sh)
shopt -u nullglob

if [ "${#hooks[@]}" -eq 0 ]; then
  log "no enabled hooks in $HOOKS_DIR — nothing to run"
  exit 0
fi

failures=0
for hook in "${hooks[@]}"; do
  name="$(basename "$hook")"
  if [ ! -x "$hook" ]; then
    log "skip $name (not executable — chmod +x to enable)"
    continue
  fi
  log "running $name"
  if "$hook"; then
    log "ok    $name"
  else
    rc=$?
    if [ "$ON_ERROR" = "continue" ]; then
      warn "failed $name (rc=$rc) — continuing (SCAFFOLD_POST_PROVISION_ON_ERROR=continue)"
      failures=$((failures + 1))
    else
      die "failed $name (rc=$rc) — aborting. Set SCAFFOLD_POST_PROVISION_ON_ERROR=continue to soften."
    fi
  fi
done

if [ "$failures" -gt 0 ]; then
  warn "$failures hook(s) failed; provision marked complete (continue mode)"
fi
