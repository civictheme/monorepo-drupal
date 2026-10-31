#!/usr/bin/env bash
# Salsa scaffold — app-container exec helper.
#
# Centralises the `docker compose exec` incantation that the ahoy commands
# share: run as the host UID/GID (so files written into the bind-mounted src/
# land with host ownership) with HOME/COMPOSER_HOME pointed at writable /tmp
# paths inside the container.
#
# Command bodies in .ahoy.yml call this script directly instead of recursing
# through `ahoy run`/`ahoy drush`/`ahoy composer`. Recursing required `ahoy`
# to be resolvable in the subshell ahoy spawns to run a command body, which is
# not guaranteed on every machine (e.g. a macOS install where `ahoy` is a shell
# alias/function, or lives in a bin dir absent from a non-interactive PATH).
# `docker` is the only host tool this depends on.
#
# Usage:
#   scripts/quant/exec.sh /opt/drupal/bin/drush status
#   scripts/quant/exec.sh composer install --no-interaction
#   scripts/quant/exec.sh bash -c 'php -v'
set -euo pipefail

# HOST_UID / HOST_GID override the calling user, falling back to it. .env.example
# has always described them as covering every wrapper that routes through here —
# composer, drush, run, test-phpunit, test-behat — but this script hardcoded
# `id -u`, so only `ahoy lint-fe` honoured them and setting either changed the
# behaviour of exactly one command.
#
# They matter where the calling user is not the one that owns the bind-mounted
# tree: a shared build box, a rootless-Docker host that remaps ids, or a CI
# runner invoking ahoy as a service account.

# CivicTheme adaptation: forward build-variant environment into the container.
# The ahoy build-*/provision-* wrappers export COMPOSER / DRUPAL_* /
# CIVICTHEME_* permutations (govcms manifest swap, content profiles, sub-theme
# toggles) in their command bodies; `docker compose exec` does not inherit the
# caller's environment, so without this the variant env never reaches
# `composer install`, provision.sh or the post-provision hooks. Same pattern
# the DrevOps `ahoy cli` wrapper used. A bare `-e NAME` forwards the value
# from the calling environment.
pass_env=()
while IFS= read -r name; do
  case "$name" in
    TERM|COMPOSER|GITHUB_*|DRUPAL_*|CIVICTHEME_*|SIMPLETEST_*|BEHAT_*|SCAFFOLD_PROVISION_TYPE|SCAFFOLD_POST_PROVISION_*)
      pass_env+=(-e "$name") ;;
  esac
done < <(compgen -e)

exec docker compose exec -T \
  --user "${HOST_UID:-$(id -u)}:${HOST_GID:-$(id -g)}" \
  -e HOME=/tmp -e COMPOSER_HOME=/tmp/.composer "${pass_env[@]}" app "$@"
