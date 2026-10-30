#!/usr/bin/env bash
# Salsa scaffold — step 1, code build.
#
# Installs PHP dependencies into the bind-mounted src/ tree. Runs LOCALLY only:
# on Salsa Hosting this step happens in GitHub Actions, baked into the image by
# .docker/drupal/Dockerfile, and the deploy just pulls that image.
#
# Local needs its own run because docker-compose.override.yml bind-mounts ./src
# over /opt/drupal, hiding the image's composer-installed tree. Without this the
# host src/ has no vendor/ and no web/core.
#
# Front-end assets are NOT built here — see docs/frontend.md. They are a project
# concern: npm in the theme directory locally, and the Dockerfile's throwaway
# `assets` stage for the image. Node deliberately never enters the app
# container.
#
# Runs on the HOST (it shells into the container itself), unlike provision.sh
# and deploy.sh which run inside it.
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

log() { printf '\033[0;36m[build]\033[0m %s\n' "$*"; }

log "composer install"
# --prefer-dist for speed; dev dependencies included, unlike the image build —
# local wants phpunit, phpcs, phpstan and rector available.
"${HERE}/exec.sh" composer install --no-interaction --prefer-dist

log "code build complete"
