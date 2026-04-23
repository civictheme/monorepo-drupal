#!/usr/bin/env bash
#
# Manage ahoy worktree instances for a DrevOps project.
#
# Each instance is a git worktree with its own docker-compose stack, routed by
# pygmy to a dedicated *.docker.amazee.io URL. Worktrees live in a sibling
# directory to the main repository so the main checkout stays untouched.
#
# Dispatched by .ahoy.worktree.yml — end users should invoke through
# `ahoy worktree <subcommand>` rather than calling this script directly.
#
# Project identity is auto-derived from the main worktree's directory name.
# Override via environment variables (set in the project's .env if desired):
#
#   WORKTREE_INSTANCE_PREFIX   prefix used for container/compose names
#                              (default: basename of main worktree)
#   WORKTREE_DIRNAME           directory where worktrees are created, as a
#                              sibling of the main repo (default: <prefix>-worktrees)
#   WORKTREE_CACHE_NAMESPACE   namespace used to name shared cache volumes
#                              (default: <prefix> with non-word chars → "_")

set -euo pipefail

_project_basename() {
  local main
  main="$(git worktree list --porcelain 2>/dev/null | awk '/^worktree /{print $2; exit}' || true)"
  if [ -z "$main" ]; then main="$PWD"; fi
  basename "$main"
}

_volume_safe() {
  printf '%s' "$1" | sed -E 's#[^A-Za-z0-9]+#_#g; s#^_+##; s#_+$##'
}

INSTANCE_PREFIX="${WORKTREE_INSTANCE_PREFIX:-$(_project_basename)}"
WORKTREES_DIRNAME="${WORKTREE_DIRNAME:-${INSTANCE_PREFIX}-worktrees}"
CACHE_NS="${WORKTREE_CACHE_NAMESPACE:-$(_volume_safe "$INSTANCE_PREFIX")}"
# Named Docker external volumes — per-project, shared across all worktrees of
# this project so a composer/npm install in one worktree populates the cache
# for the next.
CACHE_VOLUMES=("${CACHE_NS}_composer_cache" "${CACHE_NS}_npm_cache")

usage() {
  cat <<EOF
Usage: ahoy worktree <subcommand> [args]

Subcommands:
  build <branch>                  Create a worktree for <branch>, copy .env.local, and run ahoy build.
  fast-up <branch> [--from=<t>]   Create a worktree and spin it up from prebuilt images in the
                                  local registry. Default --from tag is the main repo's current
                                  branch. Skips docker build + DB import.
  ls                              List all worktree instances and their status.
  up <branch>                     Start the docker stack for <branch>.
  stop <branch>                   Stop the docker stack for <branch>.
  provision <branch>              Run 'ahoy provision' inside the worktree for <branch>.
  composer <branch> <args...>     Run 'ahoy composer <args...>' inside the worktree for <branch>.
  clear-cache                     Remove this project's composer + npm cache volumes (next build re-populates).
  rm <branch>                     Tear the stack down and remove the worktree for <branch>.

Project identity (auto-derived — override via env vars if needed):
  instance prefix:  ${INSTANCE_PREFIX}
  worktrees dir:    ../${WORKTREES_DIRNAME}/<branch>
  cache volumes:    ${CACHE_VOLUMES[0]}, ${CACHE_VOLUMES[1]}

Instance URLs are https://${INSTANCE_PREFIX}--<branch>.docker.amazee.io
EOF
}

sanitize() {
  printf '%s' "$1" | tr '[:upper:]' '[:lower:]' | sed -E 's#[^a-z0-9]+#-#g; s#^-+##; s#-+$##'
}

main_worktree_path() {
  git worktree list --porcelain | awk '/^worktree /{print $2; exit}'
}

worktrees_root() {
  echo "$(dirname "$(main_worktree_path)")/${WORKTREES_DIRNAME}"
}

worktree_dir_for() {
  echo "$(worktrees_root)/$1"
}

project_name_for() {
  echo "${INSTANCE_PREFIX}--$1"
}

url_for() {
  echo "$(project_name_for "$1").docker.amazee.io"
}

run_custom_hooks() {
  local wt="$1" branch="$2" safe="$3"
  local project; project="$(project_name_for "$safe")"
  local url; url="$(url_for "$safe")"
  local custom_dir="$wt/scripts/worktrees/custom"

  [ -d "$custom_dir" ] || return 0

  # Collect *.sh entries, sorted lexicographically.
  local scripts=()
  local f
  for f in "$custom_dir"/*.sh; do
    [ -f "$f" ] && scripts+=("$f")
  done
  [ "${#scripts[@]}" -gt 0 ] || return 0

  echo "[worktree] running ${#scripts[@]} custom hook(s) from scripts/worktrees/custom/"
  for f in "${scripts[@]}"; do
    [ -x "$f" ] || chmod +x "$f" 2>/dev/null || true
    echo "[worktree] → $(basename "$f")"
    (
      cd "$wt" && \
      WORKTREE_PATH="$wt" \
      WORKTREE_BRANCH="$branch" \
      WORKTREE_SAFE_NAME="$safe" \
      WORKTREE_PROJECT="$project" \
      WORKTREE_URL="$url" \
        bash "$f"
    ) || echo "[worktree] WARN: custom hook '$(basename "$f")' exited non-zero — continuing" >&2
  done
}

ensure_cache_volumes() {
  for v in "${CACHE_VOLUMES[@]}"; do
    if ! docker volume inspect "$v" >/dev/null 2>&1; then
      echo "[worktree] creating shared cache volume: $v"
      docker volume create "$v" >/dev/null
    fi
  done
}

# Drops a docker-compose.override.yml into the worktree that mounts the shared
# composer + npm cache volumes into the cli container. Idempotent — always
# overwrites so upgrades to the template reach existing worktrees on next run.
write_cache_override() {
  local wt="$1"
  cat > "$wt/docker-compose.override.yml" <<EOF
# Generated by ahoy worktree — do not edit.
# Mounts per-project shared cache volumes so composer/npm downloads are reused
# across worktrees of this project. To reset: ahoy worktree clear-cache

services:
  cli:
    environment:
      NPM_CONFIG_CACHE: /tmp/npm-cache
    volumes:
      - ${CACHE_VOLUMES[0]}:/tmp/.composer/cache
      - ${CACHE_VOLUMES[1]}:/tmp/npm-cache

volumes:
  ${CACHE_VOLUMES[0]}:
    external: true
  ${CACHE_VOLUMES[1]}:
    external: true
EOF
}

require_branch_arg() {
  if [ -z "${1:-}" ]; then
    echo "error: branch name required" >&2
    usage >&2
    exit 2
  fi
}

resolve_safe_name() {
  local branch="$1"
  local safe
  safe="$(sanitize "$branch")"
  if [ -z "$safe" ]; then
    echo "error: branch name is empty after sanitization: '$branch'" >&2
    exit 2
  fi
  echo "$safe"
}

sync_env_local() {
  local wt="$1" safe="$2"
  # Optional 3rd arg: explicit DREVOPS_DB_DOCKER_IMAGE (from fast-up). If not
  # given, we preserve any existing value already written to the worktree's
  # .env.local so `up`/`provision` don't silently revert a fast-up instance to
  # build-from-dump mode on a later sync.
  local db_image_override="${3:-}"
  local main project url
  main="$(main_worktree_path)"
  project="$(project_name_for "$safe")"
  url="$(url_for "$safe")"

  local preserved_db=""
  if [ -z "$db_image_override" ] && [ -f "$wt/.env.local" ]; then
    preserved_db="$(grep -E '^DREVOPS_DB_DOCKER_IMAGE=' "$wt/.env.local" 2>/dev/null | tail -n 1 | cut -d= -f2- || true)"
  fi
  local db_image="${db_image_override:-$preserved_db}"

  if [ -f "$main/.env.local" ]; then
    echo "[worktree] syncing .env.local from $main/.env.local"
    cp "$main/.env.local" "$wt/.env.local"
  else
    echo "[worktree] no .env.local in main repo — creating a minimal one"
    : > "$wt/.env.local"
  fi

  {
    echo
    echo "# --- Added by ahoy worktree: identifies this instance ---"
    echo "COMPOSE_PROJECT_NAME=$project"
    echo "DREVOPS_LOCALDEV_URL=$url"
    if [ -n "$db_image" ]; then
      echo "# --- Prebuilt DB image (from 'ahoy worktree fast-up') ---"
      echo "DREVOPS_DB_DOCKER_IMAGE=$db_image"
    fi
  } >> "$wt/.env.local"

  if grep -q '^GITHUB_TOKEN=' "$wt/.env.local"; then
    echo "[worktree] GITHUB_TOKEN present in worktree .env.local"
  else
    echo "[worktree] WARNING: GITHUB_TOKEN missing from $wt/.env.local — composer installs against private/rate-limited repos will fail" >&2
  fi
}

cmd_build() {
  require_branch_arg "${1:-}"
  local branch="$1"
  local safe; safe="$(resolve_safe_name "$branch")"
  local main; main="$(main_worktree_path)"
  local wt; wt="$(worktree_dir_for "$safe")"
  local url; url="$(url_for "$safe")"

  if [ -e "$wt" ]; then
    echo "error: worktree path already exists: $wt" >&2
    echo "hint: 'ahoy worktree rm $branch' to remove the existing instance first" >&2
    exit 1
  fi

  mkdir -p "$(dirname "$wt")"

  echo "[worktree] creating git worktree at $wt"
  if git -C "$main" show-ref --verify --quiet "refs/heads/$branch"; then
    git -C "$main" worktree add "$wt" "$branch"
  elif git -C "$main" ls-remote --exit-code --heads origin "$branch" >/dev/null 2>&1; then
    git -C "$main" worktree add --track -b "$branch" "$wt" "origin/$branch"
  else
    git -C "$main" worktree add -b "$branch" "$wt"
  fi

  sync_env_local "$wt" "$safe"
  ensure_cache_volumes
  write_cache_override "$wt"

  echo "[worktree] URL will be: https://$url"
  echo "[worktree] running 'ahoy build' inside $wt"
  (cd "$wt" && AHOY_CONFIRM_RESPONSE=y AHOY_CONFIRM_WAIT_SKIP=1 ahoy build)

  run_custom_hooks "$wt" "$branch" "$safe"
}

stack_is_running() {
  local wt="$1"
  local count
  count="$(cd "$wt" && docker compose ps -q 2>/dev/null | wc -l | tr -d ' ')"
  [ "${count:-0}" -gt 0 ]
}

cmd_ls() {
  local main root
  main="$(main_worktree_path)"
  root="$(worktrees_root)"

  printf '%-40s %-60s %-10s %s\n' "BRANCH" "URL" "STATUS" "PATH"
  git -C "$main" worktree list --porcelain | awk '/^worktree /{print $2}' | while read -r wt; do
    case "$wt" in
      "$root"/*)
        local safe status url
        safe="$(basename "$wt")"
        url="$(url_for "$safe")"
        status="stopped"
        if stack_is_running "$wt"; then
          status="running"
        fi
        printf '%-40s %-60s %-10s %s\n' "$safe" "$url" "$status" "$wt"
        ;;
    esac
  done
}

no_worktree_error() {
  local branch="$1" wt="$2"
  echo "error: no worktree for '$branch' at $wt" >&2
  echo "hint: run 'ahoy worktree build $branch' to create it, or 'ahoy worktree ls' to see existing instances" >&2
  exit 1
}

cmd_up() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  if [ ! -d "$wt" ]; then
    echo "[worktree] no worktree for '$1' — creating and building it now"
    cmd_build "$1"
    return
  fi
  sync_env_local "$wt" "$safe"
  ensure_cache_volumes
  write_cache_override "$wt"
  (cd "$wt" && ahoy up)
}

cmd_stop() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || no_worktree_error "$1" "$wt"
  (cd "$wt" && ahoy stop)
}

cmd_provision() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || no_worktree_error "$1" "$wt"
  sync_env_local "$wt" "$safe"
  shift
  (cd "$wt" && ahoy provision "$@")
}

cmd_composer() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || no_worktree_error "$1" "$wt"
  sync_env_local "$wt" "$safe"
  shift
  (cd "$wt" && ahoy composer "$@")
}

cmd_clear_cache() {
  local present=()
  for v in "${CACHE_VOLUMES[@]}"; do
    if docker volume inspect "$v" >/dev/null 2>&1; then
      present+=("$v")
    fi
  done
  if [ "${#present[@]}" -eq 0 ]; then
    echo "[worktree] no cache volumes exist for this project — nothing to do"
    return 0
  fi

  echo "[worktree] the following cache volumes will be removed:"
  printf '  - %s\n' "${present[@]}"
  if [ -z "${AHOY_CONFIRM_RESPONSE:-}" ]; then
    read -r -p ">> Proceed? [y/N] " response
  else
    response="$AHOY_CONFIRM_RESPONSE"
  fi
  case "$response" in
    y|Y|yes|true) ;;
    *) echo "aborted"; return 1 ;;
  esac

  for v in "${present[@]}"; do
    if docker volume rm "$v" >/dev/null 2>&1; then
      echo "[worktree] removed $v"
    else
      echo "[worktree] ERROR: could not remove $v — is a running container using it?" >&2
      echo "            stop any stack that mounts it (ahoy worktree stop <branch>, or 'ahoy stop' in main) and retry" >&2
      return 1
    fi
  done
  echo "[worktree] done. Next build/fast-up/up will recreate empty volumes and repopulate them."
}

read_main_env_var() {
  local key="$1" main val f
  main="$(main_worktree_path)"
  # Prefer .env.local (local-only dev settings) over .env (committed defaults).
  for f in "$main/.env.local" "$main/.env"; do
    if [ -f "$f" ]; then
      val="$(grep -E "^${key}=" "$f" 2>/dev/null | tail -n 1 | cut -d= -f2- | tr -d '"' | tr -d "'")"
      if [ -n "$val" ]; then
        echo "$val"
        return
      fi
    fi
  done
}

cmd_fast_up() {
  require_branch_arg "${1:-}"
  local branch="$1"
  shift || true
  local from_tag=""
  while [ $# -gt 0 ]; do
    case "$1" in
      --from=*) from_tag="${1#--from=}" ;;
      --from)   from_tag="${2:-}"; shift || true ;;
      *)        echo "error: unknown argument: $1" >&2; exit 2 ;;
    esac
    shift || true
  done
  # When --from isn't given, default to the branch the main repo is currently
  # on — that's what the new worktree is taken from, so the prebuilt image for
  # that branch is the natural base. Falls back to "develop" on detached HEAD.
  if [ -z "$from_tag" ]; then
    from_tag="$(git -C "$(main_worktree_path)" symbolic-ref --short HEAD 2>/dev/null || echo develop)"
    echo "[fast-up] --from not specified; defaulting to main repo's current branch: $from_tag"
  fi
  # Sanitize to a valid docker tag (same rules as image-publish.sh), so callers
  # can paste a branch name like "project/field-preprocessing" as --from.
  from_tag="$(printf '%s' "$from_tag" | sed -E 's#[^a-zA-Z0-9._-]+#-#g; s#^[-.]+##; s#[-.]+$##')"
  [ -n "$from_tag" ] || { echo "error: could not resolve a base tag" >&2; exit 2; }

  local safe; safe="$(resolve_safe_name "$branch")"
  local wt; wt="$(worktree_dir_for "$safe")"
  local project; project="$(project_name_for "$safe")"
  local url; url="$(url_for "$safe")"
  local main; main="$(main_worktree_path)"

  if [ -e "$wt" ]; then
    echo "error: worktree already exists at $wt" >&2
    echo "hint: 'ahoy worktree up $branch' to start it normally," >&2
    echo "      or 'ahoy worktree rm $branch' first to re-fast-up from a fresh image" >&2
    exit 1
  fi

  local registry namespace
  registry="$(read_main_env_var DREVOPS_IMAGE_REGISTRY)"
  namespace="$(read_main_env_var DREVOPS_IMAGE_NAMESPACE)"
  [ -n "$registry" ]  || { echo "error: DREVOPS_IMAGE_REGISTRY not set in $main/.env" >&2; exit 1; }
  [ -n "$namespace" ] || { echo "error: DREVOPS_IMAGE_NAMESPACE not set in $main/.env" >&2; exit 1; }

  local cli_remote="${registry}/${namespace}/cli:${from_tag}"
  local db_remote="${registry}/${namespace}/db:${from_tag}"

  echo "[fast-up] branch:    $branch (safe: $safe)"
  echo "[fast-up] worktree:  $wt"
  echo "[fast-up] project:   $project"
  echo "[fast-up] from-tag:  $from_tag"
  echo "[fast-up] cli image: $cli_remote"
  echo "[fast-up] db image:  $db_remote"
  echo

  echo "[fast-up] verifying images exist in registry..."
  if ! docker manifest inspect "$cli_remote" >/dev/null 2>&1; then
    echo "error: cli image '$cli_remote' not found in registry" >&2
    echo "hint: publish from the base branch first: 'ahoy image-publish $from_tag'" >&2
    exit 1
  fi
  if ! docker manifest inspect "$db_remote" >/dev/null 2>&1; then
    echo "error: db image '$db_remote' not found in registry" >&2
    echo "hint: publish from the base branch first: 'ahoy image-publish $from_tag'" >&2
    exit 1
  fi

  mkdir -p "$(dirname "$wt")"
  echo "[fast-up] creating git worktree at $wt"
  if git -C "$main" show-ref --verify --quiet "refs/heads/$branch"; then
    git -C "$main" worktree add "$wt" "$branch"
  elif git -C "$main" ls-remote --exit-code --heads origin "$branch" >/dev/null 2>&1; then
    git -C "$main" worktree add --track -b "$branch" "$wt" "origin/$branch"
  else
    git -C "$main" worktree add -b "$branch" "$wt"
  fi

  sync_env_local "$wt" "$safe" "$db_remote"
  ensure_cache_volumes
  write_cache_override "$wt"

  echo "[fast-up] pulling $cli_remote"
  docker pull "$cli_remote"
  echo "[fast-up] tagging as ${project}:latest so docker-compose uses it without rebuilding"
  docker tag "$cli_remote" "${project}:latest"

  echo "[fast-up] pulling $db_remote (mariadb service's build base)"
  docker pull "$db_remote"

  # Rebuild locally-derived services (mariadb/nginx/php) so the newly-pulled
  # bases actually land in their built images. Without this, `ahoy up` would
  # reuse a stale compose-service image from a prior fast-up (e.g. empty-DB
  # mariadb image baked in). cli is NOT rebuilt — it was pulled + tagged.
  echo "[fast-up] rebuilding mariadb/nginx/php against the freshly-pulled bases"
  (cd "$wt" && \
    DREVOPS_DB_DOCKER_IMAGE="$db_remote" \
    COMPOSE_PROJECT_NAME="$project" \
    docker compose build mariadb nginx php)

  echo "[fast-up] bringing stack up"
  (cd "$wt" && ahoy up)

  echo "[fast-up] running composer install (uses shared composer cache)"
  (cd "$wt" && ahoy composer install)

  echo "[fast-up] running 'drush deploy' (updb + config:import + cache rebuild)"
  (cd "$wt" && ahoy drush deploy) || {
    echo "[fast-up] WARN: 'drush deploy' failed — the prebuilt DB image may be stale for this branch." >&2
    echo "[fast-up]       consider 'ahoy worktree rm $branch' and 'ahoy worktree build $branch' instead." >&2
  }

  echo "[fast-up] building frontend assets (uses shared npm cache)"
  (cd "$wt" && ahoy fei && ahoy fe) || echo "[fast-up] WARN: frontend build step failed — continuing" >&2

  run_custom_hooks "$wt" "$branch" "$safe"

  echo
  echo "[fast-up] DONE. Access at: https://${url}"
}

cmd_rm() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local main; main="$(main_worktree_path)"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || no_worktree_error "$1" "$wt"

  if [ -z "${AHOY_CONFIRM_RESPONSE:-}" ]; then
    read -r -p ">> Tear down containers + volumes AND remove worktree at $wt? [y/N] " response
  else
    response="$AHOY_CONFIRM_RESPONSE"
  fi
  case "$response" in
    y|Y|yes|true) ;;
    *) echo "aborted"; exit 1 ;;
  esac

  (cd "$wt" && AHOY_CONFIRM_RESPONSE=y ahoy down) || true
  git -C "$main" worktree remove --force "$wt"
  echo "[worktree] removed $wt"
}

sub="${1:-}"
shift || true

case "$sub" in
  build)        cmd_build "$@" ;;
  fast-up)      cmd_fast_up "$@" ;;
  ls)           cmd_ls "$@" ;;
  up)           cmd_up "$@" ;;
  stop)         cmd_stop "$@" ;;
  provision)    cmd_provision "$@" ;;
  composer)     cmd_composer "$@" ;;
  clear-cache)  cmd_clear_cache "$@" ;;
  rm)           cmd_rm "$@" ;;
  ""|help|-h|--help) usage ;;
  *) echo "unknown subcommand: $sub" >&2; usage >&2; exit 2 ;;
esac
