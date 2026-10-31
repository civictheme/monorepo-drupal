#!/bin/bash
# Configure Composer's GitHub auth from the GITHUB_TOKEN env var so
# `ahoy composer install / require / update` doesn't hit GitHub's anon
# rate limit. No-op if the token isn't set.
set -euo pipefail

if [ -n "${GITHUB_TOKEN:-}" ]; then
  if composer config -g github-oauth.github.com "$GITHUB_TOKEN" 2>/dev/null; then
    echo "Composer GitHub auth configured"
  else
    echo "WARNING: failed to configure Composer GitHub auth — composer may be missing" >&2
  fi
fi
