#!/usr/bin/env bash
##
# Run Drupal provisioning steps tailored for Quant Cloud deployments.
#
# This script runs essential deployment tasks (database updates, configuration
# import, and cache rebuild) while ensuring Drush commands execute against the
# correct Quant site URL.
#
# shellcheck disable=SC1091

# Load project environment variables while preserving current exports.
t=$(mktemp) && export -p >"${t}" && set -a && . ./.env && if [ -f ./.env.local ]; then . ./.env.local; fi && set +a && . "${t}" && rm "${t}" && unset t

set -eu
[ "${DREVOPS_DEBUG-}" = "1" ] && set -x

# Helper output functions.
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }

DREVOPS_PROVISION_SKIP="${DREVOPS_PROVISION_SKIP:-0}"

if [ "${DREVOPS_PROVISION_SKIP}" = "1" ]; then
  pass "Skipped Quant provisioning because DREVOPS_PROVISION_SKIP=1."
  exit 0
fi

# Detect Quant Cloud environment (variables may be set but empty).
has_quant_environment=0
if [ -v QUANT_ENV_TYPE ] || [ -v QUANT_ENV_NAME ] || [ -v QUANT_APP_NAME ]; then
  has_quant_environment=1
fi

if [ "${has_quant_environment}" -ne 1 ]; then
  info "Quant Cloud environment not detected; nothing to do."
  exit 0
fi

info "Detected Quant Cloud environment."

# Prepare Drush URI using QUANT_ROUTE when available.
drush_uri=""
if [ -v QUANT_ROUTE ] && [ -n "${QUANT_ROUTE}" ]; then
  case "${QUANT_ROUTE}" in
    http://*|https://*)
      drush_uri="${QUANT_ROUTE%/}"
      info "Using QUANT_ROUTE for Drush URI: ${drush_uri}"
      ;;
    *)
      note "QUANT_ROUTE is set but is not a full URL. Drush will fall back to the default site context."
      ;;
  esac
elif [ -v QUANT_ROUTE ]; then
  note "QUANT_ROUTE is defined but empty. Drush will fall back to the default site context."
else
  note "QUANT_ROUTE not defined. Drush will fall back to the default site context."
fi

drush_quant() {
  if [ -n "${drush_uri}" ]; then
    ./vendor/bin/drush -y --uri="${drush_uri}" "$@"
  else
    ./vendor/bin/drush -y "$@"
  fi
}

# Check whether a comma or space separated list contains the provided token.
contains_token() {
  local needle="$1"
  local haystack="$2"
  local token trimmed

  if [ -z "${needle}" ] || [ -z "${haystack}" ]; then
    return 1
  fi

  for token in ${haystack//,/ }; do
    # Remove any whitespace characters from the token to allow comma-separated
    # or space-separated lists.
    trimmed="${token//[[:space:]]/}"
    if [ -n "${trimmed}" ] && [ "${needle}" = "${trimmed}" ]; then
      return 0
    fi
  done

  return 1
}

# Resolve the configuration source directory for the current Quant environment.
resolve_config_source() {
  local env_type="$1"
  local env_name="$2"
  local source="${default_config_dir}"

  # Normalise case and trim accidental whitespace.
  env_type="${env_type,,}"
  env_name="${env_name,,}"

  # Exclude these names from mapping to development by default.
  # By convention, Quant won't use these, but keep guard-rails in place.
  local exclude_from_dev="${QUANT_CONFIG_ENV_NAMES_EXCLUDE_FROM_DEVELOPMENT:-ci,local}"

  # The rule that determined the mapping; used for logging.
  CONFIG_SOURCE_RULE="default"

  # 1) Explicit production mapping takes precedence.
  if contains_token "${env_type}" "${production_types}" || contains_token "${env_name}" "${production_names}"; then
    source="${production_config_dir}"
    CONFIG_SOURCE_RULE="production"

  # 2) Explicit test mapping by name next.
  elif contains_token "${env_name}" "${test_names}"; then
    source="${test_config_dir}"
    CONFIG_SOURCE_RULE="test"

  # 3) Explicit development mapping by type/name.
  elif contains_token "${env_type}" "${development_types}" || contains_token "${env_name}" "${development_names}"; then
    source="${development_config_dir}"
    CONFIG_SOURCE_RULE="development-explicit"

  # 4) Fallback rule: anything not 'ci' or 'local' is development.
  elif ! contains_token "${env_name}" "${exclude_from_dev}"; then
    source="${development_config_dir}"
    CONFIG_SOURCE_RULE="development-fallback"
  else
    CONFIG_SOURCE_RULE="excluded-from-development"
  fi

  # Print both values so caller can capture without relying on subshell state.
  printf '%s\n%s' "${source}" "${CONFIG_SOURCE_RULE}"
}

# Determine if the provided configuration directory exists and contains YAML
# files. Partial or full imports should be skipped when the directory is empty
# to avoid Drush failures.
config_dir_has_files() {
  local dir="$1"

  if [ ! -d "${dir}" ]; then
    return 1
  fi

  if find "${dir}" -type f -name '*.yml' -print -quit | grep -q .; then
    return 0
  fi

  return 1
}

info "Running Drupal database updates."
drush_quant updatedb || { fail "Database updates failed."; exit 1; }
pass "Database updates complete."

default_config_dir="${QUANT_CONFIG_DIR_DEFAULT:-config/default}"
base_config_available=0

if config_dir_has_files "${default_config_dir}"; then
  info "Importing Drupal configuration from ${default_config_dir}."
  drush_quant config:import || { fail "Configuration import failed."; exit 1; }
  pass "Configuration import complete."
  base_config_available=1
else
  note "Configuration directory ${default_config_dir} is missing or empty; skipping all configuration imports."
fi

# Apply environment-specific configuration overlays only when base configuration
# exists and has been imported.
quant_env_type="${QUANT_ENV_TYPE:-}"
quant_env_name="${QUANT_ENV_NAME:-}"

if [ "${base_config_available}" -eq 1 ] && { [ -n "${quant_env_type}" ] || [ -n "${quant_env_name}" ]; }; then
  production_config_dir="${QUANT_CONFIG_DIR_PRODUCTION:-${default_config_dir}}"
  development_config_dir="${QUANT_CONFIG_DIR_DEVELOPMENT:-config/dev}"
  test_config_dir="${QUANT_CONFIG_DIR_TEST:-config/test}"

  production_types="${QUANT_CONFIG_ENV_TYPES_PRODUCTION:-production}"
  development_types="${QUANT_CONFIG_ENV_TYPES_DEVELOPMENT:-development}"

  production_names="${QUANT_CONFIG_ENV_NAMES_PRODUCTION:-production}"
  development_names="${QUANT_CONFIG_ENV_NAMES_DEVELOPMENT:-develop}"
  # Default to recognise both master and uat as test environments.
  test_names="${QUANT_CONFIG_ENV_NAMES_TEST:-master,uat}"

  # Capture both the resolved source and the rule without leaking subshell state.
  read -r config_source CONFIG_SOURCE_RULE <<<"$(resolve_config_source "${quant_env_type}" "${quant_env_name}")"

  # Always pass an absolute path to Drush to avoid CWD/docroot ambiguity.
  if [ -n "${config_source}" ] && [ "${config_source#/}" = "${config_source}" ]; then
    # Convert relative path to absolute from repository root.
    config_source="$(pwd -P)/${config_source#./}"
  fi

  info "Resolved config overlay mapping: type='${quant_env_type:-unset}', name='${quant_env_name:-unset}' -> '${config_source}' (rule: ${CONFIG_SOURCE_RULE:-default})."

  if [ -z "${config_source}" ]; then
    note "Environment-specific configuration mapping did not resolve to a directory; skipping partial import."
  elif [ "${config_source}" = "${default_config_dir}" ]; then
    note "Environment-specific configuration maps to ${config_source} (rule: ${CONFIG_SOURCE_RULE:-default}); base configuration already imported."
  elif config_dir_has_files "${config_source}"; then
    message_suffix=""
    if [ -n "${quant_env_name}" ]; then
      message_suffix=", name: ${quant_env_name}"
    fi
    info "Importing environment-specific configuration from ${config_source} (type: ${quant_env_type:-unset}${message_suffix})."
    drush_quant config:import --partial --source="${config_source}" || {
      fail "Environment-specific configuration import failed."; exit 1;
    }
    pass "Environment-specific configuration import complete."
  else
    note "Environment-specific configuration directory ${config_source} is missing or empty; skipping partial import."
  fi
elif [ "${base_config_available}" -eq 0 ]; then
  note "Skipping environment-specific configuration import because base configuration is unavailable."
else
  note "QUANT environment variables not provided; skipping environment-specific configuration import."
fi

info "Rebuilding Drupal caches."
drush_quant cache:rebuild || { fail "Cache rebuild failed."; exit 1; }
pass "Cache rebuild complete."

pass "Quant Cloud provisioning finished successfully."
