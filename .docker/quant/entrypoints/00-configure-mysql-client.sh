#!/bin/bash
# MySQL client TLS toggle.
#
# The base image's mysql CLI is MariaDB-client 11.x, which now defaults to
# TLS with server-cert verification. That rejects MySQL 8's auto-generated
# self-signed cert, breaking anything that shells out to mysql/mariadb.
#
# When DB_TLS_ENABLED=0 (local dev), drop a [client] ssl=off stanza into
# /etc/mysql/conf.d/ so a *direct* `mysql`/`mariadb` invocation matches the
# PDO branch in settings.php. In production (DB_TLS_ENABLED=1 or unset) we
# leave the defaults alone so RDS connections stay verified against the CA.
#
# IMPORTANT — this does NOT cover drush. `drush sql:query`/`sql:drop` and
# `site:install`'s internal drop_or_create invoke mariadb with
# `--defaults-file=<temp>`, which makes the client read ONLY that file and
# skip /etc/mysql/conf.d/ entirely — so this stanza is bypassed for drush.
# The drush path is handled separately by drush.yml's
# `command.sql.options.extra: --skip-ssl-verify-server-cert`, which drush only
# loads from <composer-root>/drush — i.e. /opt/drupal/drush. That is src/drush/
# in the repo, so it arrives via the ./src bind mount locally and `COPY src/`
# in the image.
set -euo pipefail

CONF=/etc/mysql/conf.d/zz-quant-disable-tls.cnf

if [ "${DB_TLS_ENABLED:-1}" = "0" ]; then
  cat > "$CONF" <<'EOF'
[client]
ssl=off
ssl-verify-server-cert=off
EOF
  chmod 644 "$CONF"
  echo "MySQL client TLS disabled (DB_TLS_ENABLED=0)"
else
  rm -f "$CONF"
fi
