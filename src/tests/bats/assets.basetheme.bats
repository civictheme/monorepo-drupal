#!/usr/bin/env bats
#
# Test for base Drupal theme assets.
#
# shellcheck disable=SC2030,SC2031,SC2129

load _helper

@test "Base Drupal theme assets" {
  dir="${DREVOPS_EXPORT_CODE_DIR:-${CUR_DIR}}/web/themes/contrib/civictheme_starter"

  pushd "${dir}" >/dev/null || exit 1

  # Files managed by the CivicTheme Drupal base theme developer.
  assert_dir_exists "assets"
  assert_dir_exists "assets/backgrounds"
  assert_dir_exists "assets/fonts"
  assert_dir_exists "assets/icons"
  assert_dir_exists "assets/logos"
  assert_dir_not_exists "assets/images"
  assert_file_not_exists "assets/images/demo1.jpg"
  assert_file_not_exists "assets/images/demo2.jpg"
  assert_file_not_exists "assets/images/demo3.jpg"
  assert_dir_not_exists "assets/videos"
  assert_file_not_exists "assets/videos/demo.avi"
  assert_file_not_exists "assets/videos/demo.mp4"
  assert_file_not_exists "assets/videos/demo.webm"
  assert_file_not_exists "assets/videos/demo_poster.png"
  assert_file_exists "assets/favicon.ico"

  # Files managed by the CivicTheme Drupal theme CI build.
  assert_dir_exists "dist"

  assert_file_exists "dist/civictheme_starter.base.css"
  assert_file_exists "dist/civictheme_starter.theme.css"
  assert_file_not_exists "dist/civictheme_starter.css"
  assert_file_not_contains "dist/civictheme_starter.css" "ck-editor__editable"

  assert_file_exists "dist/civictheme_starter.stories.css"
  assert_file_not_exists "dist/civictheme_starter.storybook.css"

  assert_file_exists "dist/civictheme_starter.variables.css"
  assert_file_contains "dist/civictheme_starter.variables.css" "--ct"

  assert_file_exists "dist/civictheme_starter.editor.css"
  assert_file_contains "dist/civictheme_starter.editor.css" "ck-editor__editable"

  assert_file_exists "dist/civictheme_starter.layout.css"
  assert_file_contains "dist/civictheme_starter.layout.css" "ct-layout"

  assert_file_exists "dist/civictheme_starter.admin.css"
  assert_file_contains "dist/civictheme_starter.admin.css" "path-admin"

  assert_file_exists "dist/civictheme_starter.base.js"
  assert_file_exists "dist/civictheme_starter.drupal.base.js"
  assert_file_not_exists "dist/civictheme_starter.js"
  assert_file_not_exists "dist/civictheme-variables.js"
  assert_file_not_exists "dist/civictheme-editor.js"

  assert_dir_exists "dist/assets"
  assert_dir_exists "dist/assets/backgrounds"
  assert_dir_not_exists "dist/assets/fonts"
  assert_dir_exists "dist/assets/icons"
  assert_dir_not_exists "dist/assets/images"
  assert_dir_exists "dist/assets/logos"
  assert_dir_not_exists "dist/assets/videos"
  assert_dir_not_exists "dist/assets/api"

  # Storybook is not part of the standard build any more (the deployed image
  # and `ahoy fe` are dist-only — Storybook is built separately, e.g. by
  # `npm run storybook` or a dedicated CI leg). Assert its shape only when a
  # Storybook build is present.
  if [ -d "storybook-static" ]; then
    assert_file_exists "storybook-static/index.html"
    assert_file_exists "storybook-static/iframe.html"
    assert_dir_exists "storybook-static/assets"
    assert_dir_exists "storybook-static/assets/backgrounds"
    assert_dir_not_exists "storybook-static/assets/fonts"
    assert_dir_exists "storybook-static/assets/icons"
    assert_dir_not_exists "storybook-static/assets/images"
    assert_dir_exists "storybook-static/assets/logos"
    assert_dir_not_exists "storybook-static/backgrounds"
    assert_dir_not_exists "storybook-static/fonts"
    assert_dir_not_exists "storybook-static/icons"
    assert_dir_not_exists "storybook-static/images"
    assert_dir_not_exists "storybook-static/logos"
    assert_dir_not_exists "storybook-static/videos"
    assert_dir_exists "storybook-static/api"
  fi

  popd >/dev/null
}
