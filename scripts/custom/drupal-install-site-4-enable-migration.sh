#!/usr/bin/env bash
##
# Enable modules.
#
# shellcheck disable=SC2086

# Use local or global Drush, giving priority to a local drush.
drush="$(if [ -f "${APP}/vendor/bin/drush" ]; then echo "${APP}/vendor/bin/drush"; else command -v drush; fi)"

echo "  > Enable migration modules."

$drush -y pm-enable civictheme_migration
