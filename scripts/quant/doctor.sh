#!/usr/bin/env bash
# Pre-flight check for host tools the scaffold depends on.
# Runs on the developer's HOST (not in the container) — `ahoy doctor`.
set -uo pipefail

PASS='\033[0;32m✓\033[0m'
FAIL='\033[0;31m✗\033[0m'
WARN='\033[0;33m!\033[0m'

status=0

check() {
  local label="$1" cmd="$2" hint="$3" optional="${4:-0}"
  if command -v "$cmd" >/dev/null 2>&1; then
    printf '  %b  %s  (%s)\n' "$PASS" "$label" "$(command -v "$cmd")"
  elif [ "$optional" = "1" ]; then
    printf '  %b  %s  — optional, install hint: %s\n' "$WARN" "$label" "$hint"
  else
    printf '  %b  %s  — install: %s\n' "$FAIL" "$label" "$hint"
    status=1
  fi
}

# Compose v2 is a `docker` subcommand, not a binary, so `command -v` cannot see
# it. The old check looked for the v1 `docker-compose` binary, which is
# end-of-life and absent from every current install — it passed only by being
# marked optional, and told you nothing either way.
check_compose() {
  local version
  if version="$(docker compose version --short 2>/dev/null)" && [ -n "$version" ]; then
    printf '  %b  %s  (v%s)\n' "$PASS" "Docker Compose v2" "$version"
  else
    printf '  %b  %s  — install: %s\n' "$FAIL" "Docker Compose v2" \
      "bundled with Docker Desktop; on Linux install the docker-compose-plugin package"
    status=1
  fi
}

echo "Salsa scaffold — host tools"
echo "----------------------------------------"

check "Docker"          docker         "https://docs.docker.com/get-docker/"
check_compose
check "Ahoy"            ahoy           "https://github.com/ahoy-cli/ahoy#installation"
check "Git"             git            "Standard package manager"
check "jq"              jq             "https://jqlang.github.io/jq/download/"
check "yq"              yq             "https://github.com/mikefarah/yq#install — optional, only for 'ahoy vscode-tasks'"  1
check "gh (GitHub CLI)" gh             "https://cli.github.com/  — used to validate GITHUB_TOKEN"

# PHP and Composer are NOT required on the host, and marking them so made
# `ahoy doctor` exit non-zero on a machine set up exactly the way README.md
# describes. Everything that needs them runs in the app container:
# `ahoy composer`, `ahoy drush`, `ahoy lint` and the tests all go through
# scripts/quant/exec.sh. They are listed only because having them is convenient
# for editor tooling.
check "PHP"             php            "not needed — the app container has it. brew install php / apt install php-cli"  1
check "Composer"        composer       "not needed — the app container has it. https://getcomposer.org/download/"      1
check "qc (Salsa Hosting CLI)"  qc       "npm install -g @quantcdn/quant-cloud-cli && qc auth login"  1
check "pygmy"           pygmy          "https://pygmy.readthedocs.io/ — needed for *.docker.amazee.io DNS on macOS"  1

# ── GITHUB_TOKEN validity ────────────────────────────────────────────────────
echo
echo "GITHUB_TOKEN (Composer auth)"
echo "----------------------------"

env_file_value() {
  # Last non-empty `KEY=` line wins (dotenv "later overrides earlier"); strip
  # surrounding quotes.
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

check_github_token() {
  local token="" source=""

  if [ -f .env ]; then
    token=$(env_file_value .env GITHUB_TOKEN)
    [ -n "$token" ] && source=".env"
  fi
  if [ -z "$token" ] && [ -n "${GITHUB_TOKEN:-}" ]; then
    token="$GITHUB_TOKEN"
    source="environment"
  fi

  if [ -z "$token" ]; then
    printf '  %b  GITHUB_TOKEN unset — composer install will be rate-limited at 60 req/h\n' "$WARN"
    printf '       Generate one with: gh auth token   (then paste into .env)\n'
    return 0
  fi

  if command -v gh >/dev/null 2>&1; then
    local user
    if user=$(GH_TOKEN="$token" gh api user --jq .login 2>/dev/null); then
      printf '  %b  GITHUB_TOKEN valid (from %s, GitHub user: %s)\n' "$PASS" "$source" "$user"
      return 0
    fi
    printf '  %b  GITHUB_TOKEN set in %s but rejected by GitHub API (gh api user failed)\n' "$FAIL" "$source"
    status=1
  elif command -v curl >/dev/null 2>&1; then
    if curl -fsS -o /dev/null -H "Authorization: token $token" https://api.github.com/user; then
      printf '  %b  GITHUB_TOKEN valid (from %s, verified via curl)\n' "$PASS" "$source"
      return 0
    fi
    printf '  %b  GITHUB_TOKEN set in %s but rejected by GitHub API\n' "$FAIL" "$source"
    status=1
  else
    printf '  %b  GITHUB_TOKEN set in %s but neither gh nor curl available — cannot validate\n' "$WARN" "$source"
  fi
}
check_github_token

echo
if [ "$status" -eq 0 ]; then
  printf '%b  All required tools present.\n' "$PASS"
else
  printf '%b  Some required tools are missing or misconfigured — see above.\n' "$FAIL"
fi
exit $status
