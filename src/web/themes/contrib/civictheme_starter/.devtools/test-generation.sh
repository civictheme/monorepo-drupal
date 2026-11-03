#!/usr/bin/env bash
##
# Generate a theme from the starterkit and assert the naming contract held.
#
# This is the guard for the generation surface: core's generate-theme blindly
# renames every case-form of the kit machine name and label, so this script
# generates a theme and asserts that the identity renamed, the content model
# and brand survived, the ignored trees stayed behind, the front-end pipeline
# builds under the generated name, and a site provisions end to end with the
# generated theme as default.
#
# Requires an assembled codebase (.devtools/assemble.sh) and a running
# webserver (.devtools/start.sh). Run from the extension root.
#
# shellcheck disable=SC2015,SC2094,SC2002

set -eu
[ -n "${DEBUG:-}" ] && set -x

#-------------------------------------------------------------------------------
# Variables (passed from environment; provided for reference only).
#-------------------------------------------------------------------------------

# Webserver hostname.
WEBSERVER_HOST="${WEBSERVER_HOST:-localhost}"

# Webserver port.
WEBSERVER_PORT="${WEBSERVER_PORT:-8000}"

# Machine name and label for the generated theme.
GENERATED_NAME="${GENERATED_NAME:-test_alpha}"
GENERATED_LABEL="${GENERATED_LABEL:-Test Alpha}"

#-------------------------------------------------------------------------------

# @formatter:off
note() { printf "       %s\n" "${1}"; }
info() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[34m[INFO] %s\033[0m\n" "${1}" || printf "[INFO] %s\n" "${1}"; }
pass() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[32m[ OK ] %s\033[0m\n" "${1}" || printf "[ OK ] %s\n" "${1}"; }
fail() { [ "${TERM:-}" != "dumb" ] && tput colors >/dev/null 2>&1 && printf "\033[31m[FAIL] %s\033[0m\n" "${1}" || printf "[FAIL] %s\n" "${1}"; }
# @formatter:on

drush() { "build/vendor/bin/drush" -r "$(pwd)/build/web" -y "$@"; }

#-------------------------------------------------------------------------------

echo "==============================="
echo "      🧪 TEST GENERATION       "
echo "==============================="
echo

extension="$(basename -s .info.yml -- ./*.info.yml)"
[ "${extension}" == "*" ] && fail "ERROR: No .info.yml file found." && exit 1

kit_dir="build/web/themes/custom/${extension}"
gen_dir="build/web/themes/custom/${GENERATED_NAME}"

# Pascal-case form of the generated label; core renames the kit's hook class
# basenames to this (label words are expected to be capitalized already).
generated_pascal="${GENERATED_LABEL// /}"

# Case-forms of the kit identity that must not survive generation
# (machine name, camel, pascal from both machine name and label, title,
# label). Everything the generated output still contains of these is a
# naming-contract breach.
old_patterns='civictheme_starter|civicthemeStarter|CivicthemeStarter|Civictheme_starter|CivicTheme Starter|civicThemeStarter|CivicThemeStarter'

info "Replacing the symlinked extension with a real copy."
# assemble.sh symlinks the extension's top-level entries into the build;
# generate-theme's file mirroring does not follow directory symlinks, so the
# kit must be a real tree. Build artifacts are left out the way a published
# kit would ship (dist/ and components/ stay in to prove the starterkit
# ignore globs drop them).
rm -rf "${kit_dir}"
mkdir -p "${kit_dir}"
rsync -a --exclude=/build --exclude=/.git --exclude=/node_modules --exclude=/storybook-static --exclude=/.logs ./ "${kit_dir}/"
pass "Extension copied to ${kit_dir}."

info "Generating theme ${GENERATED_LABEL} (${GENERATED_NAME}) from the ${extension} starterkit."
php build/vendor/bin/dr generate-theme "${GENERATED_NAME}" --name "${GENERATED_LABEL}" --starterkit "${extension}" --path themes/custom
[ -d "${gen_dir}" ] || { fail "Generated theme directory ${gen_dir} not found."; exit 1; }
pass "Theme generated."

info "Asserting no kit identity survived in file contents."
# The generator: key core writes into the generated info.yml intentionally
# records the source kit and version; it is the only allowed occurrence.
leftovers="$(grep -rIniE "${old_patterns}" "${gen_dir}" | grep -v "${GENERATED_NAME}.info.yml:.*generator:" || true)"
if [ -n "${leftovers}" ]; then
  fail "Kit identity found in generated content:"
  echo "${leftovers}"
  exit 1
fi
pass "No kit identity in file contents."

info "Asserting no kit identity survived in file names."
leftover_names="$(find "${gen_dir}" -name '*civictheme_starter*' || true)"
if [ -n "${leftover_names}" ]; then
  fail "Kit identity found in file names:"
  echo "${leftover_names}"
  exit 1
fi
pass "No kit identity in file names."

info "Asserting the identity renamed."
grep -q "Drupal\\\\${GENERATED_NAME}" "${gen_dir}/src/CivicthemeUtility.php" || { fail "Namespace did not rename in src/CivicthemeUtility.php."; exit 1; }
[ -f "${gen_dir}/src/Hook/${generated_pascal}ThemeHooks.php" ] || { fail "Hook class basenames did not rename."; exit 1; }
[ -f "${gen_dir}/config/install/${GENERATED_NAME}.settings.yml" ] || { fail "Settings config did not rename."; exit 1; }
[ -f "${gen_dir}/${GENERATED_NAME}.libraries.yml" ] || { fail "Libraries file did not rename."; exit 1; }
[ -f "${gen_dir}/assets/backgrounds/${GENERATED_NAME}_background_1.png" ] || { fail "Theme-named assets did not rename."; exit 1; }
grep -q "name: '${GENERATED_LABEL}'" "${gen_dir}/${GENERATED_NAME}.info.yml" || { fail "Label did not apply in info.yml."; exit 1; }
grep -q "generator: '${extension}:" "${gen_dir}/${GENERATED_NAME}.info.yml" || { fail "Generator provenance missing in info.yml."; exit 1; }
grep -q "function ${GENERATED_NAME}_form_system_theme_settings_alter" "${gen_dir}/theme-settings.php" || { fail "theme-settings.php hook did not rename."; exit 1; }
pass "Identity renamed."

info "Asserting the content model and brand survived."
grep -q "preprocess_paragraph__civictheme_promo" "${gen_dir}/src/Hook/Preprocess/${generated_pascal}ParagraphHooks.php" || { fail "Per-bundle hook attributes did not survive."; exit 1; }
grep -q "function civictheme_provision_cli" "${gen_dir}/theme-settings.provision.inc" || { fail "Provisioning function names did not survive."; exit 1; }
grep -q "block.block.civictheme_banner" "${gen_dir}/${GENERATED_NAME}.info.yml" || { fail "Shipped block ids did not survive."; exit 1; }
grep -q "@civictheme/uikit" "${gen_dir}/package.json" || { fail "The uikit dependency did not survive."; exit 1; }
pass "Content model and brand survived."

info "Asserting the API surface is unchanged beyond the identity."
for file in src/*.php; do
  # The post-processor never ships into generated themes.
  [ "${file}" = "src/StarterKit.php" ] && continue
  [ -f "${gen_dir}/${file}" ] || { fail "API class ${file} missing from the generated theme."; exit 1; }
  if ! diff -q \
    <(sed -e 's/civictheme_starter/__MACHINE__/g' -e 's/CivicThemeStarter/__PASCAL__/g' "${kit_dir}/${file}") \
    <(sed -e "s/${GENERATED_NAME}/__MACHINE__/g" -e "s/${generated_pascal}/__PASCAL__/g" "${gen_dir}/${file}") >/dev/null; then
    fail "API class ${file} differs beyond the identity rename."
    exit 1
  fi
done
pass "API surface unchanged."

info "Asserting ignored trees stayed behind."
for path in recipes tests dist components .devtools; do
  [ ! -e "${gen_dir}/${path}" ] || { fail "Ignored path ${path} leaked into the generated theme."; exit 1; }
done
[ ! -f "${gen_dir}/${GENERATED_NAME}.starterkit.yml" ] || { fail "starterkit.yml leaked into the generated theme."; exit 1; }
pass "Ignored trees stayed behind."

info "Building front-end in the generated theme."
# npm ci runs the postinstall script: uikit-install rewrites the component
# namespace to the generated machine name, then the full build produces dist.
(cd "${gen_dir}" && npm ci --no-audit --no-fund)
[ -f "${gen_dir}/dist/${GENERATED_NAME}.base.css" ] || { fail "dist did not build under the generated name."; exit 1; }
[ -f "${gen_dir}/dist/${GENERATED_NAME}.theme.css" ] || { fail "dist did not build under the generated name."; exit 1; }
component_count="$(grep -rl "${GENERATED_NAME}:" "${gen_dir}/components" --include='*.twig' | wc -l)"
[ "${component_count}" -gt 0 ] || { fail "Components did not register under the ${GENERATED_NAME}: namespace."; exit 1; }
stale_ns="$(grep -rn 'civictheme:' "${gen_dir}/components" --include='*.twig' || true)"
if [ -n "${stale_ns}" ]; then
  fail "Components still reference the civictheme: namespace:"
  echo "${stale_ns}"
  exit 1
fi
# The @civictheme Twig-loader namespace (icon.twig's source() asset path) is
# theme-bound too; a leftover resolves to nothing and icons silently vanish.
# Only twig and component schemas carry it - stories and generated CSS hold
# npm @civictheme/* package-scope references, which legitimately survive.
stale_loader_ns="$(grep -rn '@civictheme' "${gen_dir}/components" --include='*.twig' --include='*.component.yml' || true)"
if [ -n "${stale_loader_ns}" ]; then
  fail "Components still reference the @civictheme Twig-loader namespace:"
  echo "${stale_loader_ns}"
  exit 1
fi
pass "Front-end built under the generated name."

info "Provisioning a site with the generated theme."
PROVISION_EXTENSION="${GENERATED_NAME}" \
  PROVISION_RECIPES_DIR="$(pwd)/${kit_dir}/recipes" \
  .devtools/provision.sh
pass "Site provisioned."

info "Smoke-testing the provisioned site."
homepage="$(curl -sf "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}/")" || { fail "Homepage request failed."; exit 1; }
echo "${homepage}" | grep -q 'ct-' || { fail "Homepage does not render CivicTheme markup."; exit 1; }
echo "${homepage}" | grep -q "themes/custom/${GENERATED_NAME}/" || { fail "Homepage does not reference the generated theme's assets."; exit 1; }
if echo "${homepage}" | grep -qE "${old_patterns}"; then
  fail "Homepage markup references the kit identity."
  exit 1
fi
pass "Homepage renders with the generated theme."

#-------------------------------------------------------------------------------
# Canvas overlay pair.
#-------------------------------------------------------------------------------

canvas_gen_dir="build/web/themes/custom/${GENERATED_NAME}_canvas"
canvas_patterns='civictheme_canvas_starter|civicthemeCanvasStarter|CivicthemeCanvasStarter|Civictheme_canvas_starter|CivicTheme Canvas Starter|civicThemeCanvasStarter|CivicThemeCanvasStarter'

info "Generating the canvas overlay from the nested kit."
php build/vendor/bin/dr generate-theme "${GENERATED_NAME}_canvas" --name "${GENERATED_LABEL} Canvas" --starterkit civictheme_canvas_starter --path themes/custom
[ -d "${canvas_gen_dir}" ] || { fail "Generated overlay directory ${canvas_gen_dir} not found."; exit 1; }
pass "Overlay generated."

info "Asserting the overlay post-processing rewrote both identities."
canvas_leftovers="$(grep -rIniE "${old_patterns}|${canvas_patterns}" "${canvas_gen_dir}" | grep -v "${GENERATED_NAME}_canvas.info.yml:.*generator:" || true)"
if [ -n "${canvas_leftovers}" ]; then
  fail "Kit identity found in generated overlay content:"
  echo "${canvas_leftovers}"
  exit 1
fi
canvas_leftover_names="$(find "${canvas_gen_dir}" -name '*civictheme*' || true)"
if [ -n "${canvas_leftover_names}" ]; then
  fail "Kit identity found in overlay file names:"
  echo "${canvas_leftover_names}"
  exit 1
fi
# Core re-serializes the generated info.yml, quoting keys with spaces.
grep -qE "^'?base theme'?: ${GENERATED_NAME}$" "${canvas_gen_dir}/${GENERATED_NAME}_canvas.info.yml" || { fail "base theme did not rewrite to the main theme."; exit 1; }
grep -q "name: '${GENERATED_LABEL} Canvas'" "${canvas_gen_dir}/${GENERATED_NAME}_canvas.info.yml" || { fail "Overlay label did not apply."; exit 1; }
grep -q "generator: 'civictheme_canvas_starter:" "${canvas_gen_dir}/${GENERATED_NAME}_canvas.info.yml" || { fail "Overlay generator provenance missing."; exit 1; }
[ ! -e "${canvas_gen_dir}/src" ] || { fail "The overlay post-processor leaked into the generated overlay."; exit 1; }
[ ! -f "${canvas_gen_dir}/${GENERATED_NAME}_canvas.starterkit.yml" ] || { fail "starterkit.yml leaked into the generated overlay."; exit 1; }
[ -f "${canvas_gen_dir}/components/README.md" ] || { fail "The overrides directory did not ship."; exit 1; }
pass "Overlay rewrote to the ${GENERATED_NAME} pair."

info "Asserting an override component wins negotiation."
# The kit ships near-empty, so prove the mechanics with a throwaway override
# in the generated overlay: a component declaring it replaces the main
# theme's button.
mkdir -p "${canvas_gen_dir}/components/button"
cat > "${canvas_gen_dir}/components/button/button.component.yml" <<YAML
name: Button
status: stable
replaces: '${GENERATED_NAME}:button'
YAML
echo '<span class="generation-test-override"></span>' > "${canvas_gen_dir}/components/button/button.twig"
drush theme:enable "${GENERATED_NAME}_canvas"
drush config:set system.theme default "${GENERATED_NAME}_canvas"
drush cr
negotiated="$(drush php:eval "print \Drupal::service('Drupal\Core\Theme\ComponentNegotiator')->negotiate('${GENERATED_NAME}:button', \Drupal::service('plugin.manager.sdc')->getDefinitions()) ?? 'NONE';")"
if [ "${negotiated}" != "${GENERATED_NAME}_canvas:button" ]; then
  fail "Component negotiation returned '${negotiated}' instead of the overlay's override."
  exit 1
fi
pass "Override component wins negotiation."

info "Smoke-testing the overlay as default theme."
homepage="$(curl -sf "http://${WEBSERVER_HOST}:${WEBSERVER_PORT}/")" || { fail "Homepage request failed with the overlay."; exit 1; }
echo "${homepage}" | grep -q 'ct-' || { fail "Homepage does not render CivicTheme markup through the overlay."; exit 1; }
if echo "${homepage}" | grep -qE "${old_patterns}|${canvas_patterns}"; then
  fail "Homepage markup references a kit identity with the overlay active."
  exit 1
fi
pass "Homepage renders through the overlay."

echo
echo "==============================="
echo " 🧪 TEST GENERATION COMPLETE ✅ "
echo "==============================="
echo
