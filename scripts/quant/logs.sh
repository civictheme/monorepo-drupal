#!/usr/bin/env bash
# Salsa scaffold -- `ahoy logs` helper.
#
# Tails `docker compose logs -f` and pipes through awk to:
#   - filter by log type (errors / access / php / drupal / watchdog)
#   - colourise severity (ANSI)
#   - unescape Drupal-serialised backtraces (literal "\n" -> newline)
#   - indent stack frames (#0, #1, ...) so multi-line exceptions are scannable
#
# Usage:
#   logs.sh                       # default: pretty all-services tail
#   logs.sh <subcommand>          # errors|access|php|drupal|watchdog|raw|all
#   logs.sh <subcommand> [svc...] # also restrict to one or more compose services
#   logs.sh <service>             # bare service name (e.g. `app`) = pretty + filter
#
# Notes:
#   - Stays a thin wrapper. Only requires docker, awk -- both on a typical dev box.
#   - `raw` bypasses formatting (escape hatch if a filter ever swallows something).

set -uo pipefail

SUBCMD="${1:-all}"
case "$SUBCMD" in
  errors|access|php|drupal|watchdog|raw|all|pretty)
    shift || true
    ;;
  -h|--help|help)
    cat <<'USAGE'
ahoy logs -- pretty container log tail.

Subcommands:
  ahoy logs                Pretty tail of all services (default).
  ahoy logs errors         Errors / warnings / PHP errors / DB exceptions only.
  ahoy logs access         Apache access lines only.
  ahoy logs php            PHP notices, warnings, errors, deprecations.
  ahoy logs drupal         Drupal-emitted lines (dblog, watchdog, exceptions).
  ahoy logs watchdog       dblog write failures / watchdog table issues.
  ahoy logs raw            Plain `docker compose logs -f` (no formatting).
  ahoy logs help           This message.

Service filter (works with any subcommand):
  ahoy logs app
  ahoy logs errors app
  ahoy logs drupal app db

Formatting applied (everything except `raw`):
  - ANSI colour by severity (error=red, warn=yellow, notice=cyan, 2xx access=dim).
  - Drupal serialised backtraces unescaped: literal "\n" becomes real newlines,
    "#0 ...", "#1 ..." stack frames indented so exceptions are scannable.
USAGE
    exit 0
    ;;
  *)
    # Bare service name (or unknown token) -- treat as pretty + service filter.
    SUBCMD="pretty"
    ;;
esac

# Remaining args = compose service names to restrict to.
SERVICES=("$@")

# -- Filter regexes (awk ERE) -----------------------------------------------
# Apache access line:   "GET / HTTP/1.1" 200 ...
ACCESS_RE='"(GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS) [^"]*" [0-9]{3} '
# PHP / Apache severity tag: [php:notice], [php:warn], [php:error], [core:error]
PHP_RE='\[(php|core|proxy_fcgi|mpm_event):(notice|warn|warning|error|crit|alert|emerg|deprecated)\]'
# Drupal-flavoured signals (dblog write fail, watchdog table, Drupal\* exceptions, drush errors).
DRUPAL_RE='Drupal\\|DatabaseExceptionWrapper|watchdog|Failed to log error|drush|SQLSTATE'
WATCHDOG_RE='watchdog|Failed to log error|dblog'
# Any "error-ish" line for the `errors` subcommand.
ERRORS_RE='\[(php|core|proxy_fcgi|mpm_event):(warn|warning|error|crit|alert|emerg)\]|DatabaseExceptionWrapper|SQLSTATE|Fatal error|Uncaught|Exception|Failed to log error|\[error\]|\[ERROR\]'

# Choose filter expression for awk.
FILTER_RE=""
case "$SUBCMD" in
  errors)     FILTER_RE="$ERRORS_RE" ;;
  access)     FILTER_RE="$ACCESS_RE" ;;
  php)        FILTER_RE="$PHP_RE" ;;
  drupal)     FILTER_RE="$DRUPAL_RE" ;;
  watchdog)   FILTER_RE="$WATCHDOG_RE" ;;
  all|pretty) FILTER_RE="" ;;
esac

# -- Run docker compose logs ------------------------------------------------
if [ "$SUBCMD" = "raw" ]; then
  exec docker compose logs -f "${SERVICES[@]}"
fi

# Pipe through awk for filter + pretty-print.
# Pass FILTER_RE in via -v so awk doesn't have to escape it.
docker compose logs -f --no-color "${SERVICES[@]}" \
  | awk -v filter="$FILTER_RE" '
    BEGIN {
      # ANSI colours.
      RED  = "\033[31m"
      YEL  = "\033[33m"
      CYN  = "\033[36m"
      DIM  = "\033[2m"
      RST  = "\033[0m"
    }
    {
      line = $0

      # -- Filter ------------------------------------------------------
      if (filter != "" && line !~ filter) next

      # -- Unescape Drupal-serialised backtraces -----------------------
      # PHP exception messages often arrive with literal "\n" + escaped quotes.
      # Replace the two-char sequence "\n" with a real newline, and "\t" with tab.
      gsub(/\\n/, "\n", line)
      gsub(/\\t/, "\t", line)
      gsub(/\\"/, "\"", line)

      # Split on newline (which now exists post-unescape) and prettify each piece.
      n = split(line, parts, "\n")
      for (i = 1; i <= n; i++) {
        p = parts[i]

        # Stack-frame line -- indent so the trace is scannable.
        if (p ~ /^#[0-9]+ /) {
          p = "    " p
        }

        # -- Colourise by severity --------------------------------------
        if (p ~ /\[(php|core):(error|crit|alert|emerg)\]|Fatal error|Uncaught|DatabaseExceptionWrapper|SQLSTATE/) {
          p = RED p RST
        }
        else if (p ~ /\[(php|core):(warn|warning|deprecated)\]/) {
          p = YEL p RST
        }
        else if (p ~ /\[(php|core):notice\]/) {
          p = CYN p RST
        }
        else if (p ~ /"(GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS) [^"]*" 2[0-9]{2} /) {
          # 2xx access -- dim.
          p = DIM p RST
        }
        else if (p ~ /"(GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS) [^"]*" [45][0-9]{2} /) {
          # 4xx/5xx access -- yellow.
          p = YEL p RST
        }

        print p
      }
      fflush()
    }
  '
