#!/bin/bash
# Toggle Xdebug at container start based on SCAFFOLD_XDEBUG_ENABLED.
#
# Mechanism (matches Lagoon/Vortex/GovCMS):
#   - Build time: docker-php-ext-xdebug.ini ships with the single
#     `zend_extension=xdebug` line prefixed by `;` (commented out).
#   - Runtime: when SCAFFOLD_XDEBUG_ENABLED=1, this script uncomments that
#     line and appends `xdebug.client_host=<host>` (detected from inside the
#     container).
#   - Static xdebug.mode / client_port / etc. live in xdebug-config.ini.
#   - When SCAFFOLD_XDEBUG_ENABLED != 1, do nothing — the shipped file is
#     already in the "off" state.
set -euo pipefail

XDEBUG_INI="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"

# Find the host's IP reachable from inside the container.
#   - Docker Desktop (mac/Windows) provides `host.docker.internal` via DNS.
#   - Linux containers without the daemon's helper need the default-gateway
#     IP (the `host-gateway` alias in docker-compose.override.yml sets it up,
#     but we still fall back to a route lookup as a belt-and-braces measure).
get_dockerhost() {
  if timeout 1 getent hosts host.docker.internal >/dev/null 2>&1; then
    echo "host.docker.internal"
    return
  fi
  ip route 2>/dev/null | awk '/default/ {print $3; exit}'
}

if [ "${SCAFFOLD_XDEBUG_ENABLED:-0}" != "1" ]; then
  # Off (default). Nothing to do — the shipped ini is already commented out.
  exit 0
fi

if [ ! -f "$XDEBUG_INI" ]; then
  echo "ERROR: $XDEBUG_INI missing — base image without xdebug?" >&2
  exit 1
fi

# Uncomment the `;zend_extension=xdebug` line. The Dockerfile prefixed it with
# a two-line `;Intentionally disabled...\n;` header — strip the first line
# and remove the leading `;` from the second.
if grep -q '^;zend_extension=xdebug' "$XDEBUG_INI"; then
  sed -i '1d; s/^;//' "$XDEBUG_INI"
fi

DOCKERHOST="${DOCKERHOST:-$(get_dockerhost)}"
if [ -z "$DOCKERHOST" ]; then
  echo "ERROR: unable to resolve xdebug.client_host (set DOCKERHOST explicitly)." >&2
  exit 1
fi
sed -i '/^xdebug\.client_host=/d' "$XDEBUG_INI"
echo "xdebug.client_host=${DOCKERHOST}" >> "$XDEBUG_INI"

echo "Xdebug enabled (client_host=${DOCKERHOST})"
