#!/usr/bin/env bats
#
# Test for assets.
#
# shellcheck disable=SC2030,SC2031,SC2129

load _helper

@test "CivicTheme Demo Theme assets" {
  dir="${DREVOPS_EXPORT_CODE_DIR:-$(pwd)}/docroot/themes/custom/civictheme_demo"

  pushd "${dir}" > /dev/null || exit 1

  [ ! -d "dist" ] &&  npm run build

  # Files managed by the developer.
  assert_dir_exists "assets"
  assert_dir_exists "assets/backgrounds"
  assert_dir_exists "assets/fonts"
  assert_dir_exists "assets/icons"
  assert_dir_exists "assets/logos"
  assert_dir_exists "assets/images"
  assert_file_exists "assets/images/demo1.jpg"
  assert_file_exists "assets/images/demo2.jpg"
  assert_file_exists "assets/images/demo3.jpg"
  assert_dir_exists "assets/videos"
  assert_file_exists "assets/videos/demo.avi"
  assert_file_exists "assets/videos/demo.mp4"
  assert_file_exists "assets/videos/demo.webm"
  assert_file_exists "assets/videos/demo_poster.png"
  assert_file_exists "assets/favicon.ico"

  # Files managed by distribution build.
  assert_dir_exists "dist"
  assert_file_exists "dist/styles.css"
  assert_file_exists "dist/styles.variables.css"
  assert_file_exists "dist/styles.ckeditor.css"
  assert_file_contains "dist/styles.variables.css" "--ct"
  assert_file_exists "dist/scripts.js"
  assert_file_not_exists "dist/styles-variables.js"
  assert_file_not_exists "dist/styles-ckeditor.js"
  assert_file_exists "dist/styles.variables.csv"

  assert_dir_exists "dist/assets"
  assert_dir_exists "dist/assets/backgrounds"
  assert_dir_exists "dist/assets/fonts"
  assert_dir_exists "dist/assets/icons"
  assert_dir_exists "dist/assets/images"
  assert_dir_exists "dist/assets/logos"
  assert_dir_exists "dist/assets/videos"
  assert_dir_not_exists "dist/assets/api"

  # Files managed by static Storybook build.
  assert_dir_exists "storybook-static"
  assert_file_exists "storybook-static/index.html"
  assert_file_exists "storybook-static/iframe.html"
  assert_dir_exists "storybook-static/assets"
  assert_dir_exists "storybook-static/assets/backgrounds"
  assert_dir_exists "storybook-static/assets/fonts"
  assert_dir_exists "storybook-static/assets/icons"
  assert_dir_exists "storybook-static/assets/images"
  assert_dir_exists "storybook-static/assets/logos"
  assert_dir_exists "storybook-static/assets/videos"
  assert_dir_not_exists "storybook-static/backgrounds"
  assert_dir_not_exists "storybook-static/fonts"
  assert_dir_not_exists "storybook-static/icons"
  assert_dir_not_exists "storybook-static/images"
  assert_dir_not_exists "storybook-static/logos"
  assert_dir_not_exists "storybook-static/videos"
  assert_dir_exists "storybook-static/api"

  assert_dir_exists ".components-civictheme"
  assert_dir_exists ".components-civictheme/01-atoms/button"
  assert_file_exists ".components-civictheme/01-atoms/button/button.twig"
  assert_dir_not_exists ".components-civictheme/01-atoms/demo-button"
  assert_file_exists ".components-civictheme/02-molecules/navigation-card/navigation-card.twig"
  assert_file_not_contains ".components-civictheme/02-molecules/navigation-card/navigation-card.twig" "Demonstration of adding an extension of the existing molecule-level"
  assert_file_exists ".components-civictheme/03-organisms/header/header.twig"
  assert_file_not_contains ".components-civictheme/03-organisms/header/header.twig" "Example of extending of the base CivicTheme component."

  assert_dir_exists "components_combined"
  assert_dir_exists "components_combined/01-atoms/button"
  assert_file_exists "components_combined/01-atoms/button/button.twig"
  assert_dir_exists "components_combined/01-atoms/demo-button"
  assert_file_exists "components_combined/01-atoms/demo-button/demo-button.twig"
  assert_file_exists "components_combined/02-molecules/navigation-card/navigation-card.twig"
  assert_file_contains "components_combined/02-molecules/navigation-card/navigation-card.twig" "Demonstration of adding an extension of the existing molecule-level"
  assert_file_exists "components_combined/03-organisms/header/header.twig"
  assert_file_contains "components_combined/03-organisms/header/header.twig" "Example of extending of the base CivicTheme component."

  popd > /dev/null
}
