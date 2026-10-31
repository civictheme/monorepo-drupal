#!/bin/bash
# Local-dev entrypoint: iterates /quant-entrypoint.d/ then hands off.
# In Salsa Hosting the platform wrapper runs these scripts itself.
set -e

if [ -d "/quant-entrypoint.d" ]; then
  for script in /quant-entrypoint.d/*.sh; do
    if [ -f "$script" ] && [ -x "$script" ]; then
      echo "Running $(basename "$script")..."
      "$script"
    fi
  done
fi

exec docker-php-entrypoint "$@"
