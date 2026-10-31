#!/usr/bin/env bash
#
# Generate .vscode/tasks.json (+ sidecar scripts) from .ahoy.yml.
#
# Each Ahoy command becomes a VSCode task. To keep the tasks working without
# the `ahoy` binary on PATH (e.g. a machine where `ahoy` is a shell alias or
# lives in a bin dir absent from VSCode's environment), each command body is
# written verbatim to a sidecar script under .vscode/ahoy-tasks/<name>.sh and
# the task runs that script via `bash`. .ahoy.yml stays the single source of
# truth — no bodies are hand-maintained; re-run this generator after editing it.
#
# Writing the body to a file (rather than inlining it into the task's command
# string) avoids two traps: shell-quoting the body into JSON, and VSCode's
# ${...} variable substitution mangling shell expansions like ${VAR:-default}.
# VSCode only ever scans the task command/args, which are just a script path
# plus the optional args prompt.
#
# Commands that forward arguments ($@/$*) get an interactive args prompt.
# Hidden commands (hide: true) are skipped.
#
# Usage:
#   ahoy vscode-tasks          # from the project root
#   scripts/quant/vscode-tasks.sh [project-root]
#
# Re-run any time .ahoy.yml changes. Requires yq (mikefarah, v4+) at generation
# time only; running the generated tasks needs just bash + docker.
set -euo pipefail

script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
root="${1:-$(cd "$script_dir/../.." && pwd)}"
ahoy_file="$root/.ahoy.yml"
out_dir="$root/.vscode"
out_file="$out_dir/tasks.json"
sidecar_dir="$out_dir/ahoy-tasks"

if ! command -v yq >/dev/null 2>&1; then
  echo "[vscode-tasks] yq not found — skipping. Install yq, then run 'ahoy vscode-tasks'." >&2
  exit 0
fi

if [ ! -f "$ahoy_file" ]; then
  echo "[vscode-tasks] $ahoy_file not found." >&2
  exit 1
fi

# (Re)create the sidecar dir from scratch so a command later removed from
# .ahoy.yml doesn't leave an orphan script (and stale task) behind.
rm -rf "$sidecar_dir"
mkdir -p "$sidecar_dir"

# Write one sidecar script per non-hidden command, holding the command body
# verbatim — no escaping, and no injected `set -e` (each body keeps its own,
# e.g. build opts in, lint deliberately doesn't). Tasks invoke these with
# `bash <path>`, so the executable bit is irrelevant.
while IFS= read -r name; do
  [ -z "$name" ] && continue
  safe="$(printf '%s' "$name" | sed 's/[^a-zA-Z0-9_]/_/g')"
  {
    echo '#!/usr/bin/env bash'
    name="$name" yq -r '.commands[env(name)].cmd // ""' "$ahoy_file"
  } > "$sidecar_dir/$safe.sh"
done < <(yq -r '.commands | to_entries | map(select(.value.hide != true)) | .[].key' "$ahoy_file")

# yq (mikefarah v4) program. Builds the full tasks.json structure so all string
# escaping is handled natively. mikefarah has no if/then/else — the args suffix
# is selected with `(... | select($bool)) // ""`. Each task runs its sidecar
# script; the script name is the command key with non-word chars folded to `_`.
read -r -d '' program <<'YQ' || true
.commands as $c
| ($c | to_entries | map(select(.value.hide != true))) as $cmds
| {
    "version": "2.0.0",
    "tasks": (
      $cmds | map(
        (.key | sub("[^a-zA-Z0-9_]"; "_")) as $safe
        | (((.value.cmd // "") | test("\$@|\$\*")) as $hasargs
        | {
            "label": .key,
            "type": "shell",
            "command": (
              "bash \"${workspaceFolder}/.vscode/ahoy-tasks/" + $safe + ".sh\""
              + ((" ${input:args_" + $safe + "}" | select($hasargs)) // "")
            ),
            "problemMatcher": [],
            "detail": ((.value.usage // "") | sub("\n"; " ") | sub(" {2,}"; " ") | trim)
          })
      )
    ),
    "inputs": (
      $cmds
      | map(select((.value.cmd // "") | test("\$@|\$\*")))
      | map(
        {
          "id": ("args_" + (.key | sub("[^a-zA-Z0-9_]"; "_"))),
          "type": "promptString",
          "description": ("Arguments for `" + .key + "` (leave empty for none)"),
          "default": ""
        }
      )
    )
  }
YQ

body="$(yq -o=json "$program" "$ahoy_file")"
count="$(printf '%s' "$body" | yq -p=json '.tasks | length')"

mkdir -p "$out_dir"
{
  echo "// Generated from .ahoy.yml by scripts/quant/vscode-tasks.sh — do not edit by hand."
  echo "// Regenerate after changing .ahoy.yml: ahoy vscode-tasks"
  echo "// Task bodies live in .vscode/ahoy-tasks/<name>.sh (also generated)."
  printf '%s\n' "$body"
} > "$out_file"

echo "[vscode-tasks] Wrote $count tasks to ${out_file#"$root"/} (+ sidecar scripts in ${sidecar_dir#"$root"/})"
