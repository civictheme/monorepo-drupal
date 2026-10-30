#!/usr/bin/env bash
##
# Build CivicTheme front-end assets on the HOST.
#
# The scaffold app container has no node; ./src is bind-mounted over
# /opt/drupal, so host output is live immediately. The deployed image builds
# the same assets in the Dockerfile's assets stage — keep the two recipes in
# step (npm ci --ignore-scripts, uikit-install, dist).
#
# Usage:
#   scripts/custom/fe-build.sh             # base theme (components/ + dist/)
#   scripts/custom/fe-build.sh --subtheme  # generated sub-theme, if present
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

log()  { printf '\033[0;36m[fe-build]\033[0m %s\n' "$*"; }
warn() { printf '\033[0;33m[fe-build]\033[0m %s\n' "$*" >&2; }

if ! command -v npm >/dev/null 2>&1; then
  warn "npm not found on host - skipping front-end build."
  warn "Install node >= 22 (see the theme's package.json engines) and run 'ahoy fe'."
  exit 0
fi

if [ "${1:-}" = "--subtheme" ]; then
  for dir in "$HERE/src/web/themes/custom/civictheme_demo" "$HERE/src/web/themes/contrib/civictheme_demo"; do
    if [ -d "$dir" ]; then
      log "building sub-theme: ${dir#"$HERE"/}"
      (cd "$dir" && npm install --no-audit --no-fund && npm run dist)
      exit 0
    fi
  done
  log "no generated sub-theme found - nothing to build"
  exit 0
fi

dir="$HERE/src/web/themes/contrib/civictheme"
log "building base theme: ${dir#"$HERE"/}"
(
  cd "$dir"
  # Stale node_modules (from another branch or an old uikit pin) produce
  # "Undefined mixin" sass failures: uikit-install copies components/ FROM
  # node_modules. npm ci is clean-room (it removes node_modules first); skip
  # it only when the installed tree matches the current lockfile.
  if [ ! -d node_modules ] || [ package-lock.json -nt node_modules/.package-lock.json ]; then
    # --ignore-scripts skips the theme's postinstall (uikit-install + full
    # build incl. Storybook); the two steps below run just what dist needs.
    npm ci --no-audit --no-fund --ignore-scripts
  fi
  # components/ is populated from node_modules by uikit-install, which merges
  # rather than replaces — a stale tree survives it. Same reset the image
  # build's assets stage does.
  rm -rf components dist
  npm run uikit-install
  npm run dist
)
