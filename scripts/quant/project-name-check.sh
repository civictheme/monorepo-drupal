#!/usr/bin/env bash
#
# Guard against silently booting on the wrong database after the compose
# project name moved from "whatever the directory is called" to
# ${SCAFFOLD_PROJECT}.
#
# docker-compose.override.yml now declares `name: ${SCAFFOLD_PROJECT}`. A
# project created before that had its Docker objects named after its directory
# basename, so on the first `up` afterwards Compose looks for
# <SCAFFOLD_PROJECT>_db_data, doesn't find it, and creates an empty one — the
# site comes back up with no content and no error. The old volume still holds
# the data, which makes this recoverable but bewildering.
#
# This refuses to start in exactly that situation and prints the two ways out.
# It stays silent otherwise: matching names, a database already under the new
# name, a brand new project, or no docker on PATH.
#
# Usage:
#   scripts/quant/project-name-check.sh          # called by `ahoy up` / `ahoy build`

set -uo pipefail

VOLUME_SUFFIX="${DB_VOLUME_SUFFIX:-db_data}"

die() { printf '\033[0;31m[project-name]\033[0m %s\n' "$*" >&2; exit 1; }

# No docker, no volumes to reason about — `ahoy doctor` covers that.
command -v docker >/dev/null 2>&1 || exit 0

# Last non-empty `KEY=` line wins (dotenv "later overrides earlier"); strip
# surrounding quotes. Same reader as doctor.sh.
env_file_value() {
  local file="$1" key="$2"
  [ -f "$file" ] || return 0
  awk -v k="$key" '
    $0 ~ "^[[:space:]]*"k"=" {
      sub("^[[:space:]]*"k"=","");
      gsub(/["'"'"']/,"");
      if (length($0) > 0) last=$0;
    }
    END { if (last) print last }
  ' "$file"
}

# What Compose will use: COMPOSE_PROJECT_NAME beats the compose file's `name:`,
# which is ${SCAFFOLD_PROJECT}. ahoy exports .env into this script's
# environment, so the env vars are normally already set; the .env fallback is
# for direct invocation, where they are not.
resolved="${COMPOSE_PROJECT_NAME:-${SCAFFOLD_PROJECT:-}}"
if [ -z "$resolved" ]; then
  resolved="$(env_file_value .env COMPOSE_PROJECT_NAME)"
  [ -n "$resolved" ] || resolved="$(env_file_value .env SCAFFOLD_PROJECT)"
fi

if [ -z "$resolved" ]; then
  die "SCAFFOLD_PROJECT is empty and COMPOSE_PROJECT_NAME is unset.
       Compose cannot name this project. Set SCAFFOLD_PROJECT in .env:
         SCAFFOLD_PROJECT=$(basename "$PWD" | tr '[:upper:]' '[:lower:]')
       Older projects may still carry the pre-rename PROJECT_NAME= line;
       rename it (see docs/variables.md)."
fi

# What Compose used before the `name:` field existed.
legacy="$(basename "$PWD" | tr '[:upper:]' '[:lower:]')"
[ "$legacy" != "$resolved" ] || exit 0

volume_exists() { docker volume inspect "$1" >/dev/null 2>&1; }

# Already has a database under the resolved name — nothing to migrate.
volume_exists "${resolved}_${VOLUME_SUFFIX}" && exit 0
# No database under the old name either — brand new project.
volume_exists "${legacy}_${VOLUME_SUFFIX}" || exit 0

die "this project's database is under the OLD compose project name.

       Docker objects here used to be named after the directory
       (\"${legacy}\"). The scaffold now names them after SCAFFOLD_PROJECT
       (\"${resolved}\"), so starting now would create an empty
       ${resolved}_${VOLUME_SUFFIX} and the site would come up with no content.

       Your data is safe in ${legacy}_${VOLUME_SUFFIX}. Pick one:

       (a) Keep the old name — add this to .env, one line, done:
             COMPOSE_PROJECT_NAME=${legacy}

       (b) Move to the new name — copy the database across (~1s), then start:
             ahoy down:force
             scripts/quant/worktree-clone.sh ${legacy} ${resolved}

       See docs/worktrees.md."
