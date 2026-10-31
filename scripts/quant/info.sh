#!/usr/bin/env bash
# Salsa scaffold — print project URLs and ports.
#
# Host-side only (needs `docker` + the project env); never enters a container
# shell. Called directly by the `info`, `up`, and `build` ahoy commands so
# those bodies don't recurse through `ahoy info` (see exec.sh for why nested
# `ahoy` calls are avoided).
#
# PROJECT name comes from SCAFFOLD_PROJECT (set in .env, which ahoy loads). No
# fallback: urls.sh already dies with a clear message on an empty value, and a
# placeholder default here would mask that and print a URL for the wrong site.
#
# The URL line(s) respect SCAFFOLD_DISPLAY_URL (pygmy|both|docker; default both)
# and are produced by the sibling urls.sh — the single validation + resolution
# point shared with `ahoy login`. See docs/variables.md.
set -uo pipefail

PROJECT="${SCAFFOLD_PROJECT:-}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo
echo "Salsa scaffold — ${PROJECT}"
# urls.sh validates SCAFFOLD_DISPLAY_URL and derives the pygmy URL from
# $SCAFFOLD_PROJECT / the docker URL from `docker compose port app 80`.
"$SCRIPT_DIR/urls.sh" info
# `docker compose port db 3306` outputs e.g. `0.0.0.0:49154` (or `[::]:49154`
# for IPv6). `awk -F: '{print $NF}'` grabs the trailing port — empty when
# the container isn't running. Kept here (not in urls.sh) so the helper
# stays URL-only.
db_port=$(docker compose port db 3306 2>/dev/null | awk -F: '{print $NF}')
echo "  DB host port: ${db_port:-not running}"
echo
