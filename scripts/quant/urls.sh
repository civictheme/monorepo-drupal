#!/usr/bin/env bash
# Resolve and print the project's site URL(s) according to SCAFFOLD_DISPLAY_URL.
#
# SCAFFOLD_DISPLAY_URL is an enum controlling which URL(s) the scaffold shows:
#   pygmy  → DNS URL only        (http://<project>.docker.amazee.io)
#   docker → docker localhost URL only (http://localhost:<dynamic-port>)
#   both   → show both                 (default)
#
# This helper is the single validation + resolution point shared by
# `ahoy info` and `ahoy login` (see .ahoy.yml). It is deliberately pure: it
# computes host/port → text and never generates a drush login token, so the
# token-once invariant (below) lives in .ahoy.yml and this stays bats-testable.
#
# URL sources (single source of truth):
#   pygmy  — derived from $SCAFFOLD_PROJECT (loaded from .env by ahoy), never a
#            name baked in at scaffold time, so the helper is rename-safe.
#   docker — `docker compose port app 80` (empty when app isn't running, same
#            as the old inline `ahoy info` behaviour).
#
# Usage:
#   scripts/quant/urls.sh info          # print the info-style URL lines
#   scripts/quant/urls.sh login-bases   # emit selected base URL(s), one per
#                                        # line, no trailing slash, for `login`
#                                        # to render its uli path against
#   scripts/quant/urls.sh -h|--help

set -euo pipefail

die() { printf '\033[0;31m[urls]\033[0m %s\n' "$*" >&2; exit 2; }

usage() {
  sed -n '2,25p' "$0" | sed 's/^# \{0,1\}//'
}

# Validate SCAFFOLD_DISPLAY_URL (default `both`) and echo the resolved mode.
# Invalid value dies loudly, listing the valid set (fail-loud convention).
resolve_mode() {
  local mode="${SCAFFOLD_DISPLAY_URL:-both}"
  case "$mode" in
    pygmy|both|docker) printf '%s\n' "$mode" ;;
    *) die "Invalid SCAFFOLD_DISPLAY_URL='$mode'. Expected: pygmy | both | docker" ;;
  esac
}

# Pygmy / amazee.io DNS URL, derived from $SCAFFOLD_PROJECT.
pygmy_url() {
  local project="${SCAFFOLD_PROJECT:-}"
  [ -n "$project" ] || die "SCAFFOLD_PROJECT is empty — cannot derive the pygmy URL. Set it in .env."
  printf 'http://%s.docker.amazee.io\n' "$project"
}

# Host port the app's :80 is published on, or empty when not running.
# `docker compose port app 80` outputs e.g. `0.0.0.0:49154` (or `[::]:49154`
# for IPv6); `awk -F: '{print $NF}'` grabs the trailing port.
app_port() {
  docker compose port app 80 2>/dev/null | awk -F: '{print $NF}'
}

# Docker localhost URL, or empty when the app isn't running.
docker_url() {
  local port
  # `|| true` is required, not defensive. `set -euo pipefail` is on; when the
  # app is not running `docker compose port` exits non-zero, pipefail hands that
  # to the pipeline, the command substitution propagates it to the assignment,
  # and `set -e` kills the script — before the emptiness check below could ever
  # run. So the documented "app not running" branch, and the message `ahoy info`
  # prints for it, were both unreachable.
  port="$(app_port || true)"
  [ -n "$port" ] || return 0
  printf 'http://localhost:%s\n' "$port"
}

cmd_info() {
  local mode
  mode="$(resolve_mode)"

  if [ "$mode" = "pygmy" ] || [ "$mode" = "both" ]; then
    local purl
    purl="$(pygmy_url)"
    printf '  Local URL:  %s  (via pygmy)\n' "$purl"
  fi

  if [ "$mode" = "docker" ] || [ "$mode" = "both" ]; then
    local durl
    durl="$(docker_url)"
    if [ -n "$durl" ]; then
      printf '  Direct:     %s\n' "$durl"
    else
      printf '  Direct:     (app not running — start the stack with `ahoy up`)\n'
    fi
  fi
}

# Emit the selected base URL(s), one per line, no trailing slash. `ahoy login`
# renders its (host-independent) uli path against each. In `docker`/`pygmy`
# mode a single base is emitted; in `both`, pygmy first then docker. A
# not-running app in docker mode emits nothing (degrade, not `http://localhost:`).
cmd_login_bases() {
  local mode
  mode="$(resolve_mode)"

  if [ "$mode" = "pygmy" ] || [ "$mode" = "both" ]; then
    pygmy_url
  fi

  if [ "$mode" = "docker" ] || [ "$mode" = "both" ]; then
    docker_url
  fi
}

main() {
  case "${1-}" in
    -h|--help) usage; exit 0 ;;
    info) cmd_info ;;
    login-bases) cmd_login_bases ;;
    "") die "Missing subcommand. Try: info | login-bases (or --help)." ;;
    *) die "Unknown subcommand '$1'. Try: info | login-bases (or --help)." ;;
  esac
}

main "$@"
