#!/usr/bin/env bash
##
# Update default content from the current site.
#
# Usage:
# ./scripts/update-theme-config.sh
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

CONTENT_TYPE="${CONTENT_TYPE:-default}"

#-------------------------------------------------------------------------------

echo "==> Started updating content in ${CONTENT_TYPE} content profile."

echo "  > Removing generated content."
drush pm-uninstall -y generated_content || true

echo "  > Removing users."
drush entity:delete user

echo "  > Exporting content."
drush dcer --folder="modules/custom/civictheme_content/modules/civictheme_content_${CONTENT_TYPE}/content" taxonomy_term
drush dcer --folder="modules/custom/civictheme_content/modules/civictheme_content_${CONTENT_TYPE}/content" node
drush dcer --folder="modules/custom/civictheme_content/modules/civictheme_content_${CONTENT_TYPE}/content" block_content
drush dcer --folder="modules/custom/civictheme_content/modules/civictheme_content_${CONTENT_TYPE}/content" menu_link_content

echo "==> Finished updating content in ${CONTENT_TYPE} content profile."
