#!/usr/bin/env bash
##
# Update default content from the current site.
#
# Usage:
# ./scripts/update.default-content.sh
#

set -eu
[ -n "${DREVOPS_DEBUG:-}" ] && set -x

CIVICTHEME_CONTENT_PROFILE="${CIVICTHEME_CONTENT_PROFILE:-default}"

#-------------------------------------------------------------------------------

echo "==> Started updating content in ${CIVICTHEME_CONTENT_PROFILE} content profile."

echo "  > Removing generated content."
drush pm-uninstall -y generated_content || true

echo "  > Removing users."
drush entity:delete user

echo "  > Removing existing content files."
rm -Rf "/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content/"* || true

echo "  > Exporting content."
drush dcer --folder="/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content" taxonomy_term
drush dcer --folder="/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content" node
drush dcer --folder="/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content" block_content
drush dcer --folder="/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content" menu_link_content
drush dcer --folder="/app/recipes/civictheme_content_${CIVICTHEME_CONTENT_PROFILE}/content" media

echo "  > Enabling config_devel."
drush pm-enable -y config_devel || true

echo "  > Exporting config."
drush -q cde "civictheme_content_${CIVICTHEME_CONTENT_PROFILE}"

echo "==> Finished updating content in ${CIVICTHEME_CONTENT_PROFILE} content profile."
