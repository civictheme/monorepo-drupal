#!/usr/bin/env bash
##
# Update (or check) the CivicTheme recipe config from the current site config.
#
# Routes every config item owned by a recipe — per the manifest at
# web/themes/contrib/civictheme_starter/.devtools/recipe-manifest.json — from a full
# site export into the owning recipe's config/ directory, with the uuid and
# _core site identity stripped. This replaces the retired `drush cde` flow for
# the content model; theme-owned config (civictheme_starter.settings and the block
# placements) still exports through config_devel via update.theme-config.sh.
#
# Usage (inside the app container, from /opt/drupal):
#   ./scripts/update.recipe-config.sh          # write into the recipe dirs
#   ./scripts/update.recipe-config.sh check    # diff only, non-zero on drift

set -eu
[ -n "${SCAFFOLD_DEBUG:-}" ] && set -x

MODE="${1:-update}"

# Theme dir.
THEME_DIR="web/themes/contrib/civictheme_starter"

# Manifest mapping config names to owning recipes.
MANIFEST="${THEME_DIR}/.devtools/recipe-manifest.json"

# Temp dirs.
TMP_DIR="/tmp/civictheme-recipes"
TMP_EXPORT="${TMP_DIR}/export"
TMP_ROUTED="${TMP_DIR}/routed"

#-------------------------------------------------------------------------------

[ ! -f "${MANIFEST}" ] && echo "ERROR: ${MANIFEST} does not exist" && exit 1

rm -rf "${TMP_DIR:?}"
mkdir -p "${TMP_EXPORT}" "${TMP_ROUTED}"

echo "  > Exporting current config"
drush -q cex -y --destination="${TMP_EXPORT}"

echo "  > Routing recipe-owned config per the manifest"
while IFS=$'\t' read -r name recipe; do
  src="${TMP_EXPORT}/${name}.yml"
  if [ ! -f "${src}" ]; then
    echo "WARNING: '${name}' (recipe ${recipe}) is not present in the active site config."
    continue
  fi
  mkdir -p "${TMP_ROUTED}/${recipe}/config"
  # Strip the uuid and the _core hash: recipe config ships without site
  # identity, matching what the recipe system expects.
  awk 'BEGIN{skip=0} /^_core:/{skip=1;next} skip==1&&/^[^ ]/{skip=0} skip==1{next} /^uuid: /{next} {print}' "${src}" >"${TMP_ROUTED}/${recipe}/config/${name}.yml"
done < <(jq -r '.config | to_entries[] | select(.value | test("^civictheme_")) | [.key, .value] | @tsv' "${MANIFEST}")

if [ "${MODE}" = "check" ]; then
  echo "  > Comparing recipe config against the active site"
  failed=0
  for recipe_dir in "${TMP_ROUTED}"/*; do
    recipe="$(basename "${recipe_dir}")"
    diff -r "${THEME_DIR}/recipes/${recipe}/config" "${recipe_dir}/config" || failed=1
  done
  if [ "${failed}" -ne 0 ]; then
    echo "ERROR: Recipe configuration differs from the active site. Run ./scripts/update.recipe-config.sh and review the changes."
    exit 1
  fi
  echo "  > OK: Recipe configuration is consistent."
else
  echo "  > Writing recipe config"
  for recipe_dir in "${TMP_ROUTED}"/*; do
    recipe="$(basename "${recipe_dir}")"
    rm -rf "${THEME_DIR}/recipes/${recipe}/config"
    cp -R "${recipe_dir}/config" "${THEME_DIR}/recipes/${recipe}/config"
  done
  echo "  > Done. Review the changes and commit."
fi
