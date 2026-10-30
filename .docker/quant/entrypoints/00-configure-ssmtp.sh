#!/bin/bash
# Mail routing: MailHog locally, AWS SES on Salsa Hosting.
set -euo pipefail

CONF=/etc/ssmtp/ssmtp.conf

write_conf() {
  # Write atomically with restrictive perms — credentials live here.
  umask 077
  cat > "$CONF"
  chmod 600 "$CONF"
  if id -u root >/dev/null 2>&1; then
    chown root:root "$CONF" 2>/dev/null || true
  fi
}

if [ "${SCAFFOLD_MAILHOG_ENABLED:-0}" = "1" ]; then
  # Derived from SCAFFOLD_PROJECT (passed through by the app service's
  # environment: block in docker-compose.override.yml) rather than baked in at
  # scaffold time, so a rename or a worktree moves it with one .env line.
  write_conf <<EOF
mailhub=amazeeio-mailhog:1025
FromLineOverride=YES
hostname=${SCAFFOLD_PROJECT:-localhost}.local
EOF
  echo "SSMTP configured for local MailHog"
elif [ -n "${SSMTP_MAILHUB:-}" ]; then
  : "${SSMTP_AUTHUSER:?SSMTP_AUTHUSER required when SSMTP_MAILHUB is set}"
  : "${SSMTP_AUTHPASS:?SSMTP_AUTHPASS required when SSMTP_MAILHUB is set}"
  hostname_value="${SSMTP_REWRITEDOMAIN:-$(hostname -f 2>/dev/null || echo localhost)}"
  {
    printf 'mailhub=%s\n'        "$SSMTP_MAILHUB"
    printf 'AuthUser=%s\n'       "$SSMTP_AUTHUSER"
    printf 'AuthPass=%s\n'       "$SSMTP_AUTHPASS"
    # AWS SES requires TLS — default both flags ON unless the operator
    # explicitly opts out via env var.
    printf 'UseTLS=%s\n'         "${SSMTP_USETLS:-YES}"
    printf 'UseSTARTTLS=%s\n'    "${SSMTP_USESTARTTLS:-YES}"
    if [ "${SSMTP_USETLS:-YES}" = "NO" ] && [ "${SSMTP_USESTARTTLS:-YES}" = "NO" ]; then
      echo "WARNING: SSMTP TLS and STARTTLS both disabled — credentials will be sent in plaintext" >&2
    fi
    if [ -n "${SSMTP_REWRITEDOMAIN:-}" ]; then
      printf 'rewriteDomain=%s\n' "$SSMTP_REWRITEDOMAIN"
    fi
    printf 'FromLineOverride=YES\n'
    printf 'hostname=%s\n'       "$hostname_value"
  } | write_conf
  echo "SSMTP configured for Salsa Hosting (AWS SES): ${SSMTP_MAILHUB}"
else
  echo "SSMTP_MAILHUB unset and not in local mode — skipping SSMTP configuration"
fi
