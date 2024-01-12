# CLI container.
#
# Not used in Lagoon.
#
# - Installs Composer dependencies
# - Installs CivicTheme dependencies and builds assets
# - Creates sub-theme as a sibling, installs dependencies and builds assets
#
# @see https://hub.docker.com/r/uselagoon/php-8.2-cli-drupal/tags
# @see https://github.com/uselagoon/lagoon-images/tree/main/images/php-cli-drupal
FROM uselagoon/php-8.2-cli-drupal:23.12.0

# Add missing variables.
# @todo Remove once https://github.com/uselagoon/lagoon/issues/3121 is resolved.
ARG LAGOON_PR_HEAD_BRANCH=""
ENV LAGOON_PR_HEAD_BRANCH=${LAGOON_PR_HEAD_BRANCH}
ARG LAGOON_PR_HEAD_SHA=""
ENV LAGOON_PR_HEAD_SHA=${LAGOON_PR_HEAD_SHA}

# Webroot is used for Drush aliases.
ARG WEBROOT=web

ARG GITHUB_TOKEN=""
ENV GITHUB_TOKEN=${GITHUB_TOKEN}

# Set default values for environment variables.
# These values will be overridden if set in docker-compose.yml or .env file
# during build stage.
ENV WEBROOT=${WEBROOT} \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/.composer/cache \
    SIMPLETEST_DB=mysql://drupal:drupal@mariadb/drupal \
    SIMPLETEST_BASE_URL=http://nginx:8080 \
    SYMFONY_DEPRECATIONS_HELPER=disabled

# Strating from this line, Docker will add result of each command into a
# separate layer. These layers are then cached and re-used when the project is
# rebuilt.
# Note that layers are only rebuilt if files added into the image with `ADD`
# have changed since the last build. So, adding files that are unlikely to
# change earlier in the build process (closer to the start of this file)
# reduce build time.

# Adding more tools.
RUN apk update \
    && apk add pv python3 make gcc g++ diffutils ncurses=6.4_p20230506-r0 pv=1.6.20-r1 tzdata=2023d-r0 \
    && ln -sf python3 /usr/bin/python \
    && rm -rf /var/cache/apk/*

# Install updated version of NPM.
RUN npm install -g npm@^8.6 && fix-permissions /home/.npm

# Adding patches and scripts.
COPY patches /app/patches
COPY scripts /app/scripts

RUN mkdir -p web/themes/contrib/civictheme \
    && mkdir -p web/modules/custom/civictheme_govcms \
    && mkdir -p web/modules/custom/civictheme_admin \
    && mkdir -p web/modules/custom/civictheme_content \
    && mkdir -p web/modules/custom/civictheme_dev \
    && mkdir -p web/modules/custom/cs_generated_content

# Copy files required for PHP dependencies resolution.
# Note that composer.lock is not explicitly copied, allowing to run the stack
# without existing lock file (this is not advisable, but allows to build
# using latest versions of packages). composer.lock should be comitted to the
# repository.
# File .env (and other environment files) is copied into image as it may be
# required by composer scripts to get some additions variables.
COPY composer.json composer.* .env* auth* /app/

COPY web/themes/contrib/civictheme/composer.json /app/web/themes/contrib/civictheme/
COPY web/modules/custom/civictheme_govcms/composer.json web/modules/custom/civictheme_govcms/
COPY web/modules/custom/civictheme_admin/composer.json web/modules/custom/civictheme_admin/
COPY web/modules/custom/civictheme_content/composer.json web/modules/custom/civictheme_content/
COPY web/modules/custom/civictheme_dev/composer.json web/modules/custom/civictheme_dev/
COPY web/modules/custom/cs_generated_content/composer.json web/modules/custom/cs_generated_content/

# Install PHP dependencies without including development dependencies. This is
# crucial as it prevents potential security vulnerabilities from being exposed
# to the production environment.
RUN if [ -n "${GITHUB_TOKEN}" ]; then export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"; fi && \
    COMPOSER_MEMORY_LIMIT=-1 composer install -n --no-dev --ansi --prefer-dist --optimize-autoloader

# Install NodeJS dependencies.
COPY web/themes/contrib/civictheme/ web/themes/contrib/civictheme/package* /app/web/themes/contrib/civictheme/

# Install NodeJS dependencies.
RUN npm --prefix web/themes/contrib/civictheme install --no-audit --no-progress --unsafe-perm

# Copy all files into appllication source directory. Existing files are always
# overridden.
COPY . /app

# Compile front-end assets. Running this after copying all files as we need
# sources to compile assets.
RUN cd /app/web/themes/contrib/civictheme && npm run build

# Create a sub-theme in the same directory as CivicTheme.
RUN cd /app/web/themes/contrib/civictheme \
  && php civictheme_create_subtheme.php civictheme_demo "CivicTheme Demo Sibling" "Demo sub-theme for a CivicTheme theme installed in the same directory." ../civictheme_demo

# Compile sub-theme assets.
RUN npm --prefix web/themes/contrib/civictheme_demo install --no-audit --no-progress --unsafe-perm \
  && cd /app/web/themes/contrib/civictheme_demo && npm run build
