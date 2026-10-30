#!/usr/bin/env bash
# Salsa scaffold — LOCAL provisioning entrypoint.
#
# Step 0 (bootstrap) plus a hand-off to deploy.sh for steps 2 and 3:
#
#   0. bootstrap        fresh install from a profile/recipe, or import a dump
#   2. database updates -> deploy.sh
#   3. post-provision   -> deploy.sh
#
# Mode-aware: SCAFFOLD_PROVISION_TYPE chooses between install and import.
#
# LOCAL ONLY. Bootstrap drops every table, and database mode reads a dump from
# /import, which is a local-only bind mount. Salsa Hosting deploys call
# deploy.sh directly — they pull a pre-built image and must never re-provision.
# See docs/deployment.md.
#
# DRUPAL_INSTALL_PROFILE accepts ANY of:
#   - a profile machine name (e.g. govcms, standard, minimal), OR
#   - an absolute container path to a recipe directory (e.g.
#     /opt/drupal/recipes/civictheme_site_install), OR
#   - a recipe path relative to APP_ROOT (e.g. recipes/civictheme_site_install)
#     — any value containing a slash but not starting with one is resolved to
#     "${APP_ROOT}/<value>", so you need not know the container layout.
# Drupal 10.3+ treats a recipe path passed to `drush site:install` as a fresh
# install via that recipe (no separate `drush recipe` step needed). The resolved
# absolute recipe path is logged so it is clear which recipe ran.
set -euo pipefail

# Default matches .env.example and configure.sh. It used to be `database`,
# so deleting the line from .env silently switched a profile-install
# project into importing a dump it may not have.
SCAFFOLD_PROVISION_TYPE="${SCAFFOLD_PROVISION_TYPE:-profile}"
DRUSH="${DRUSH:-/opt/drupal/bin/drush}"
DRUPAL_INSTALL_PROFILE="${DRUPAL_INSTALL_PROFILE:-standard}"
APP_ROOT="${APP_ROOT:-/opt/drupal}"
# MUST match settings.php's config_sync_directory — same env var, same default.
# It previously read DRUPAL_CONFIG_PATH, defaulting to
# web/sites/default/files/config/default. Nothing ever set DRUPAL_CONFIG_PATH,
# so the default always won and always pointed at a directory that does not
# exist, silently disabling config:import, the post-import UUID restore, and
# --existing-config. Read the value from Drupal instead of duplicating the
# default? No — profile mode needs it BEFORE site:install, when there is no
# bootstrapped Drupal to ask. smoke.sh asserts the two stay in agreement.
CONFIG_PATH="${DRUPAL_CONFIG_SYNC:-${APP_ROOT}/config/sync}"
DB_FILE="${DB_FILE:-/import/db.sql}"
# Neutral last-resort defaults. configure.sh writes the project's real values
# into .env, and docker-compose.override.yml passes both through to this
# container, so these only fire if .env is missing the keys entirely.
# Deliberately NOT the scaffold's placeholder name — see download-db.sh for why
# no file under scripts/quant/ should carry that literal.
SITE_NAME="${DRUPAL_SITE_NAME:-Drupal}"
SITE_EMAIL="${DRUPAL_SITE_EMAIL:-admin@example.com}"
ADMIN_NAME="${DRUPAL_ADMIN_NAME:-admin}"
HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

log()  { printf '\033[0;36m[provision]\033[0m %s\n' "$*"; }
warn() { printf '\033[0;33m[provision]\033[0m %s\n' "$*" >&2; }
die()  { printf '\033[0;31m[provision]\033[0m %s\n' "$*" >&2; exit 2; }

restore_site_uuid() {
  if [ -f "${CONFIG_PATH}/system.site.yml" ]; then
    local uuid
    uuid="$(awk '/^uuid:/ {gsub(/[ '\''"]/, "", $2); print $2; exit}' "${CONFIG_PATH}/system.site.yml")"
    if [ -n "$uuid" ]; then
      "$DRUSH" config-set system.site uuid "$uuid" -y
      log "Restored site UUID: $uuid"
    fi
  fi
}

# Drop every table in the active database.
#
# Replaces `drush sql:drop`, which emits a single multi-table
# `DROP TABLE \`a\`, \`b\`, …` statement WITHOUT `IF EXISTS`. On re-provision
# after a partial/aborted install, the DB ends up in a mixed state where some
# tables drush expects no longer exist; the multi-drop then fails with
# `ERROR 1051 (42S02) Unknown table 'drupal.X,drupal.Y,…'` and provisioning
# stops. We enumerate the live table list, then issue one DROP TABLE IF EXISTS
# with FOREIGN_KEY_CHECKS off to handle FK constraints between content and
# revision tables. Idempotent — a no-op when the database is already empty.
# List the live tables of a given type as a comma-separated `quoted` list.
# Crucially this does NOT swallow stderr: if drush sql:query can't connect (e.g.
# the MariaDB client rejecting MySQL's self-signed cert because drush.yml's
# --skip-ssl-verify-server-cert never loaded), the real error reaches the
# terminal and we `die` with context — instead of `set -e -o pipefail` killing
# the whole provision silently mid-assignment.
list_tables() {
  local type="$1" out rc=0
  # stdout (the table list) is captured; stderr flows to the terminal so a
  # connection failure is visible. `|| rc=$?` keeps set -e from aborting here.
  out="$("$DRUSH" sql:query "SHOW FULL TABLES WHERE Table_type='${type}'")" || rc=$?
  if [ "$rc" -ne 0 ]; then
    die "Could not query existing ${type} list (drush sql:query exit ${rc}). Is the database reachable, and is drush.yml loaded (drush status → 'Drush configs')? See the TLS error above."
  fi
  printf '%s\n' "$out" | awk 'NF { printf "%s`%s`", (n++ ? "," : ""), $1 } END { print "" }'
}

drop_all_tables() {
  # `SHOW TABLES` includes views; `DROP TABLE IF EXISTS <view>` silently no-ops
  # (IF EXISTS suppresses the "not a table" error), so views would survive.
  # Split into base tables vs views and drop each with the right statement.
  local base_tables views
  base_tables="$(list_tables 'BASE TABLE')"
  views="$(list_tables 'VIEW')"
  if [ -z "${base_tables}${views}" ]; then
    log "Database is already empty — nothing to drop."
    return 0
  fi
  log "Dropping existing tables to prepare for fresh provision."
  # `if` blocks, not `[ -n "$x" ] && cmd`. As a function's LAST statement, a
  # short-circuiting `&&` makes the failed test the function's exit status — so
  # a database holding views but no base tables returned 1 from a successful
  # drop, and `set -e` killed the provision before site:install ever ran.
  if [ -n "$views" ]; then
    "$DRUSH" sql:query "DROP VIEW IF EXISTS ${views};"
  fi
  if [ -n "$base_tables" ]; then
    "$DRUSH" sql:query "SET FOREIGN_KEY_CHECKS=0; DROP TABLE IF EXISTS ${base_tables}; SET FOREIGN_KEY_CHECKS=1;"
  fi
}

case "$SCAFFOLD_PROVISION_TYPE" in
  profile)
    # DRUPAL_INSTALL_PROFILE may be a recipe path (starts with /) or a
    # profile machine name. A value containing a slash but not starting with one
    # (e.g. recipes/<name>) is a recipe path relative to APP_ROOT — resolve it to
    # an absolute path so the validation, log line, and drush opts all agree. A
    # slash-less value (e.g. standard) stays a profile machine name.
    if [[ "$DRUPAL_INSTALL_PROFILE" != /* && "$DRUPAL_INSTALL_PROFILE" == */* ]]; then
      DRUPAL_INSTALL_PROFILE="${APP_ROOT}/${DRUPAL_INSTALL_PROFILE}"
    fi
    if [[ "$DRUPAL_INSTALL_PROFILE" = /* ]]; then
      [ -d "$DRUPAL_INSTALL_PROFILE" ] || die "Recipe directory not found: $DRUPAL_INSTALL_PROFILE"
      [ -f "$DRUPAL_INSTALL_PROFILE/recipe.yml" ] || die "Not a recipe — missing recipe.yml: $DRUPAL_INSTALL_PROFILE"
      log "Mode: install from recipe ($DRUPAL_INSTALL_PROFILE)"
    else
      log "Mode: install from profile ($DRUPAL_INSTALL_PROFILE)"
    fi

    opts=(
      "$DRUPAL_INSTALL_PROFILE"
      --site-name="$SITE_NAME"
      --site-mail="$SITE_EMAIL"
      --account-name="$ADMIN_NAME"
      install_configure_form.enable_update_status_module=NULL
      install_configure_form.enable_update_status_emails=NULL
    )
    [ -n "${DRUPAL_ADMIN_EMAIL:-}" ] && opts+=(--account-mail="$DRUPAL_ADMIN_EMAIL")
    # Re-import shipped config if a config sync directory is populated.
    if [ -d "$CONFIG_PATH" ] && find "$CONFIG_PATH" -maxdepth 1 -name '*.yml' -print -quit | grep -q .; then
      opts+=(--existing-config)
    fi

    # Make sure DB is empty before install.
    drop_all_tables
    "$DRUSH" site:install -y "${opts[@]}"
    ;;

  database)
    log "Mode: database import (file=${DB_FILE})"
    if [ ! -f "$DB_FILE" ]; then
      die "Database-import provision needs an existing dump at ${DB_FILE}, and none is present — refusing to provision (no auto-download).
  Local dev: provide the dump, then re-run:
    ahoy download-db                   # pull a timestamped dump into .data/
    ahoy mysql:select-db-dump --latest # hard-link it to .data/db.sql
    ahoy provision                     # re-run database import
  Want a fresh site instead? Set SCAFFOLD_PROVISION_TYPE=profile."
    fi
    drop_all_tables
    "$DRUSH" sql:query --file="$DB_FILE"
    restore_site_uuid
    ;;

  *)
    die "Unknown SCAFFOLD_PROVISION_TYPE: '$SCAFFOLD_PROVISION_TYPE' (expected: profile|database)"
    ;;
esac

# Bootstrap is done; hand off to the deploy steps (database updates + post-
# provision hooks). deploy.sh is the same script Salsa Hosting runs on a cloud
# deploy, so what happens here and what happens there cannot drift apart.
#
# CivicTheme adaptation: mark this deploy as following a fresh provision.
# deploy.sh (and therefore the post-provision hooks) also runs on every server
# container start via the /quant-entrypoint.d/ path; hooks whose work is only
# valid right after `drush site:install` (file wipe, content generation) gate
# on this marker and no-op otherwise.
export SCAFFOLD_FRESH_PROVISION=1
exec "${HERE}/deploy.sh"
