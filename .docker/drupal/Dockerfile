# Build args shared across stages. These MUST stay above the first FROM — an
# ARG declared before any FROM is global, one declared after belongs to that
# stage only.
#
# PHP_VERSION: 8.4 for the deployed (Drupal 11) image; the local govcms stack
# builds with 8.3 to match the GovCMS platform PHP (see docker-compose.yml).
# NODE_VERSION: matches the theme's package.json engines (node >= 22).
ARG PHP_VERSION=8.4
ARG NODE_VERSION=22

# ─────────────────────────────────────────────────────────────────────────────
# Stage: sub-theme generation.
#
# civictheme_demo is generated, never committed. The pipeline invariant says
# servers never run composer or npm, so the deployed image must carry the
# generated AND built sub-theme. Generation is a standalone PHP script (no
# Drupal bootstrap), so it runs in a php-capable stage; the node assets stage
# below copies the result next to the base theme and builds it.
#
# Uses the same base image as the final stage so no extra base is pulled.
# ─────────────────────────────────────────────────────────────────────────────
FROM ghcr.io/quantcdn-templates/app-apache-php:${PHP_VERSION} AS subtheme

COPY src/web/themes/contrib/civictheme /build/themes/contrib/civictheme
RUN cd /build/themes/contrib/civictheme \
 && php civictheme_create_subtheme.php civictheme_demo "CivicTheme Demo" \
      "Demo sub-theme for a CivicTheme theme." \
      /build/themes/custom/civictheme_demo --remove-examples \
 && test -f /build/themes/custom/civictheme_demo/package.json

# ─────────────────────────────────────────────────────────────────────────────
# Stage: front-end assets.
#
# A throwaway stage — node, npm and node_modules never reach the final image;
# only the compiled output is copied across.
#
# Written as explicit steps rather than the scaffold's `*/` loop because the
# build order matters (the sub-theme's dist reads the base theme's components)
# and the base theme needs `uikit-install` before `dist`. The deployed image
# is dist-only — Storybook is NOT built here (decided 2026-10-29; Storybook
# moves to a CI-built static deploy).
#
# The path must keep a `themes/` segment: the themes' build.js locates the
# base theme by looking for a directory named exactly `themes` in its path.
# ─────────────────────────────────────────────────────────────────────────────
FROM node:${NODE_VERSION}-alpine AS assets

# The themes' build.js shells out to rsync; node:alpine has none, and build.js
# catches the ENOENT, prints a stack trace and exits 0 — a green log and an
# image with no CSS. The dist/ assertions below are the backstop. git: the
# theme pins @civictheme/* packages to git shas, which npm clones.
RUN apk add --no-cache rsync git

WORKDIR /build/themes

# Base theme. `--ignore-scripts` skips its postinstall (uikit-install + full
# build incl. Storybook); the explicit steps run just what the image needs.
# `uikit-install` populates components/ from the pinned @civictheme/sdc
# package — the SDC components are runtime content, so they ship too.
COPY src/web/themes/contrib/civictheme ./contrib/civictheme
RUN set -eu; \
    cd contrib/civictheme; \
    rm -rf components dist node_modules; \
    npm ci --no-audit --no-fund --ignore-scripts; \
    npm run uikit-install; \
    npm run dist; \
    [ -d components ] && [ -n "$(ls -A components)" ] \
      || { echo "components/ is missing or empty after uikit-install." >&2; exit 1; }; \
    [ -d dist ] && [ -n "$(ls -A dist)" ] \
      || { echo "dist/ is missing or empty after the base theme build." >&2; exit 1; }

# Sub-theme, built AFTER the base theme — its dist reads the base theme's
# components. npm scripts stay enabled here: the generated package.json's
# postinstall performs its own uikit/component wiring (same flow the old
# provision step used).
COPY --from=subtheme /build/themes/custom/civictheme_demo ./custom/civictheme_demo
RUN set -eu; \
    cd custom/civictheme_demo; \
    if [ -f package-lock.json ]; then npm ci --no-audit --no-fund; \
    else npm install --no-audit --no-fund; fi; \
    npm run dist; \
    [ -d dist ] && [ -n "$(ls -A dist)" ] \
      || { echo "dist/ is missing or empty after the sub-theme build." >&2; exit 1; }; \
    rm -rf node_modules

# Collect only what ships: base theme compiled output + the whole generated
# sub-theme (it does not exist in src/, so the final stage needs all of it).
RUN set -eu; \
    mkdir -p /export/contrib/civictheme /export/custom; \
    cp -R contrib/civictheme/components /export/contrib/civictheme/components; \
    cp -R contrib/civictheme/dist /export/contrib/civictheme/dist; \
    cp -R custom/civictheme_demo /export/custom/civictheme_demo

# ─────────────────────────────────────────────────────────────────────────────
# Stage: lift the built assets back onto the host (`ahoy fe-build`).
#
# FROM scratch so `docker build --target assets-export --output
# type=local,dest=src/web/themes` writes contrib/civictheme/{components,dist}
# and custom/civictheme_demo onto the host tree and nothing else. Nothing
# depends on this stage, so a normal build never runs it.
# ─────────────────────────────────────────────────────────────────────────────
FROM scratch AS assets-export
COPY --from=assets /export/ /

# ─────────────────────────────────────────────────────────────────────────────
# Final image.
# ─────────────────────────────────────────────────────────────────────────────
FROM ghcr.io/quantcdn-templates/app-apache-php:${PHP_VERSION}

# Optional Composer GitHub auth — passed via `docker compose build`'s args.
# Trade-off: this ARG is recoverable from `docker history` so don't bake
# production tokens into shared images. Use BuildKit secrets if that matters.
ARG GITHUB_TOKEN=""

ENV DOCUMENT_ROOT=/opt/drupal/web
WORKDIR /opt/drupal

# Xdebug + ssmtp (mail relay for SES / MailHog) + git (Composer needs it to
# install packages from VCS/git sources) + jq (for post-provision.d hooks).
#
# Xdebug is installed-but-disabled by default; the runtime entrypoint
# (01-xdebug.sh) uncomments the zend_extension line when enabled.
RUN pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && sed -i '1s|^|;Intentionally disabled. Enable via SCAFFOLD_XDEBUG_ENABLED=1\n;|' \
      /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && apt-get update \
 && apt-get install -y --no-install-recommends git ssmtp jq \
 && rm -rf /var/lib/apt/lists/*

# Shipshape binary, baked in so `ahoy ship-shape` runs it inside this container
# (which has php) — its phpstan checks exec the php-based phpstan binary.
COPY --from=ghcr.io/salsadigitalauorg/shipshape:latest /usr/local/bin/shipshape /usr/local/bin/shipshape

# Composer files first to maximise layer caching. The root manifest uses
# wikimedia/composer-merge-plugin over the committed theme/module manifests,
# so those must be present before `composer install`.
COPY src/composer.json src/composer.lock* ./
COPY src/web/themes/contrib/civictheme/composer.json ./web/themes/contrib/civictheme/
COPY src/web/modules/custom/civictheme_admin/composer.json ./web/modules/custom/civictheme_admin/
COPY src/web/modules/custom/civictheme_content/composer.json ./web/modules/custom/civictheme_content/
COPY src/web/modules/custom/civictheme_dev/composer.json ./web/modules/custom/civictheme_dev/
COPY src/web/modules/custom/cs_generated_content/composer.json ./web/modules/custom/cs_generated_content/

# Patches must be present before `composer install` so cweagans/composer-patches
# can apply any project-defined patches during the production image build. The
# later `COPY src/` overlays the same content harmlessly.
COPY src/patches/ ./patches/

# Production image: no-dev install (smaller, no test/lint frameworks shipped).
# Local dev re-installs WITH dev deps via `ahoy composer install` after the
# bind mount overlays this layer — see docker-compose.override.yml.
ARG COMPOSER_NO_DEV=true
RUN set -eu; \
    if [ -n "$GITHUB_TOKEN" ]; then \
      composer config -g github-oauth.github.com "$GITHUB_TOKEN"; \
    fi; \
    composer config apcu-autoloader true; \
    if [ "$COMPOSER_NO_DEV" = "true" ]; then \
      NO_DEV_FLAG="--no-dev"; \
    else \
      NO_DEV_FLAG=""; \
    fi; \
    COMPOSER_MEMORY_LIMIT=-1 composer install \
      -n --ansi --prefer-dist --optimize-autoloader --apcu-autoloader $NO_DEV_FLAG; \
    if [ -n "$GITHUB_TOKEN" ]; then \
      composer config -g --unset github-oauth.github.com; \
    fi

# Deployment scripts (called by /quant-entrypoint.d/02-post-deploy.sh).
COPY .docker/deployment-scripts /quant/deployment-scripts
# Whole scripts/ dir (quant + custom + project scripts) — the one tree the
# container needs under /opt/drupal that is not in src/; it also runs on the
# host, so it lives at the project root.
COPY scripts /opt/drupal/scripts

# Salsa Hosting platform entrypoints.
COPY .docker/quant/entrypoints/ /quant-entrypoint.d/
RUN if [ "$(ls -A /quant-entrypoint.d/)" ]; then chmod +x /quant-entrypoint.d/*; fi

# Custom PHP overrides (numeric prefix sets order; 90- = app-level).
COPY .docker/quant/php.ini.d/ /usr/local/etc/php/conf.d/

RUN chmod +x /quant/deployment-scripts/* /opt/drupal/scripts/quant/*.sh 2>/dev/null \
 && usermod -a -G www-data nobody \
 && usermod -a -G www-data root

# Source code last; rebuilds on every change.
COPY src/ /opt/drupal/

# Compiled front-end output, overlaid on the source tree. MUST come after
# `COPY src/` — the other way round, the source copy overwrites the built
# assets with the un-built tree.
COPY --from=assets /export/contrib/civictheme/components ./web/themes/contrib/civictheme/components
COPY --from=assets /export/contrib/civictheme/dist ./web/themes/contrib/civictheme/dist
COPY --from=assets /export/custom/civictheme_demo ./web/themes/custom/civictheme_demo

RUN chown -R www-data:www-data web/sites web/modules web/themes

# Identifies THIS build. post-deploy.sh compares it against the id recorded in
# Drupal state to tell a deploy (new image → fresh reinstall) from an
# idle-sleep wake-up or task restart (same image → deploy steps only) — the
# platform itself offers no such signal. CI passes a value unique per workflow
# run; the "dev" default means a locally built image never looks like a new
# deploy to a server. Declared last so changing it only rebuilds this layer.
ARG BUILD_ID=dev
RUN printf '%s' "$BUILD_ID" > /opt/drupal/.build-id

ENV PATH=${PATH}:/opt/drupal/bin

# Local dev shim (used only when docker-compose.override.yml swaps the entrypoint).
COPY .docker/drupal/docker-entrypoint.sh /usr/local/bin/docker-entrypoint-drupal.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-drupal.sh

EXPOSE 80
ENTRYPOINT ["docker-php-entrypoint"]
CMD ["apache2-foreground"]
