#!/usr/bin/env bash
#
# Manage ahoy worktree instances.
#
# Each instance is a git worktree with its own docker-compose stack, routed by
# pygmy to a dedicated *.docker.amazee.io URL. Worktrees live in a sibling
# directory to the main repository so the main checkout stays untouched.
#
# Dispatched by .ahoy.worktree.yml — end users should invoke through
# `ahoy worktree <subcommand>` rather than calling this script directly.

set -euo pipefail

INSTANCE_PREFIX="civictheme-mono"
WORKTREES_DIRNAME="civictheme-worktrees"

usage() {
  cat <<EOF
Usage: ahoy worktree <subcommand> [args]

Subcommands:
  build <branch>   Create a worktree for <branch>, copy .env.local, and run ahoy build.
  ls               List all worktree instances and their status.
  up <branch>      Start the docker stack for <branch>.
  stop <branch>    Stop the docker stack for <branch>.
  rm <branch>      Tear the stack down and remove the worktree for <branch>.

Instances are created under ../${WORKTREES_DIRNAME}/<branch>/ and exposed at
https://${INSTANCE_PREFIX}--<branch>.docker.amazee.io
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

cmd_build() {
  require_branch_arg "${1:-}"
  local branch="$1"
  local safe; safe="$(resolve_safe_name "$branch")"
  local main; main="$(main_worktree_path)"
  local wt; wt="$(worktree_dir_for "$safe")"
  local project; project="$(project_name_for "$safe")"
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

  if [ -f "$main/.env.local" ]; then
    echo "[worktree] copying .env.local from main repo"
    cp "$main/.env.local" "$wt/.env.local"
  else
    : > "$wt/.env.local"
  fi

  cat >> "$wt/.env.local" <<EOF

# --- Added by ahoy worktree: identifies this instance ---
COMPOSE_PROJECT_NAME=$project
DREVOPS_LOCALDEV_URL=$url
EOF

  echo "[worktree] URL will be: https://$url"
  echo "[worktree] running 'ahoy build' inside $wt"
  (cd "$wt" && AHOY_CONFIRM_RESPONSE=y AHOY_CONFIRM_WAIT_SKIP=1 ahoy build)
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

cmd_up() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || { echo "error: no worktree for '$1' at $wt" >&2; exit 1; }
  (cd "$wt" && ahoy up)
}

cmd_stop() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || { echo "error: no worktree for '$1' at $wt" >&2; exit 1; }
  (cd "$wt" && ahoy stop)
}

cmd_rm() {
  require_branch_arg "${1:-}"
  local safe; safe="$(resolve_safe_name "$1")"
  local main; main="$(main_worktree_path)"
  local wt; wt="$(worktree_dir_for "$safe")"
  [ -d "$wt" ] || { echo "error: no worktree for '$1' at $wt" >&2; exit 1; }

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
  build) cmd_build "$@" ;;
  ls)    cmd_ls "$@" ;;
  up)    cmd_up "$@" ;;
  stop)  cmd_stop "$@" ;;
  rm)    cmd_rm "$@" ;;
  ""|help|-h|--help) usage ;;
  *) echo "unknown subcommand: $sub" >&2; usage >&2; exit 2 ;;
esac
