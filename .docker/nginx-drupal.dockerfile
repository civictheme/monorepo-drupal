# Nginx container.
#
# All web requests are sent to this container.
ARG CLI_IMAGE
# hadolint ignore=DL3006
FROM ${CLI_IMAGE:-cli} as cli

# @see https://hub.docker.com/r/uselagoon/nginx-drupal/tags?page=1
# @see https://github.com/uselagoon/lagoon-images/tree/main/images/nginx-drupal
FROM uselagoon/nginx-drupal:25.11.0

# Webroot is used for Nginx web configuration.
ARG WEBROOT=web
ENV WEBROOT=${WEBROOT}

RUN apk add --no-cache tzdata
COPY .docker/entrypoints/nginx/* /quant-entrypoint.d/

# Copy custom nginx configuration for CDN header handling
COPY .docker/config/nginx/location_drupal_prepend_host.conf /etc/nginx/conf.d/drupal/
RUN chmod 0644 /etc/nginx/conf.d/drupal/location_drupal_prepend_host.conf
COPY --from=cli /app /app
