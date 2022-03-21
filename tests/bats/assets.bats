#!/usr/bin/env bats
#
# Test for assets.
#
# shellcheck disable=SC2030,SC2031,SC2129

load _helper

@test "Civic Library assets" {
  dir="docroot/themes/contrib/civic/civic-library"

  # Files managed by the developer.
  assert_dir_exists "$dir/assets"
  assert_dir_exists "$dir/assets/backgrounds"
  assert_dir_exists "$dir/assets/fonts"
  assert_dir_exists "$dir/assets/icons"
  assert_dir_exists "$dir/assets/logos"
  assert_dir_exists "$dir/assets/images"
  assert_file_exists "$dir/assets/images/demo.png"
  assert_dir_exists "$dir/assets/videos"
  assert_file_exists "$dir/assets/videos/demo.avi"
  assert_file_exists "$dir/assets/videos/demo.mp4"
  assert_file_exists "$dir/assets/videos/demo.webm"
  assert_file_exists "$dir/assets/videos/demo_poster.png"
  assert_file_exists "$dir/assets/favicon.ico"

  # Files managed by distribution build.
  assert_dir_exists "$dir/dist"
  assert_file_exists "$dir/dist/civic.css"
  assert_file_exists "$dir/dist/civic.js"
  assert_dir_exists "$dir/dist/assets"
  assert_dir_exists "$dir/dist/assets/backgrounds"
  assert_dir_not_exists "$dir/dist/assets/fonts"
  assert_dir_exists "$dir/dist/assets/icons"
  assert_dir_exists "$dir/dist/assets/images"
  assert_dir_exists "$dir/dist/assets/logos"
  assert_dir_exists "$dir/dist/assets/videos"
  assert_dir_not_exists "$dir/dist/assets/api"

  # Files managed by static Storybook build.
  assert_dir_exists "$dir/storybook-static"
  assert_file_exists "$dir/storybook-static/index.html"
  assert_file_exists "$dir/storybook-static/iframe.html"
  assert_dir_exists "$dir/storybook-static/assets"
  assert_dir_exists "$dir/storybook-static/assets/backgrounds"
  assert_dir_not_exists "$dir/storybook-static/assets/fonts"
  assert_dir_exists "$dir/storybook-static/assets/icons"
  assert_dir_exists "$dir/storybook-static/assets/images"
  assert_dir_exists "$dir/storybook-static/assets/logos"
  assert_dir_exists "$dir/storybook-static/assets/videos"
  assert_dir_not_exists "$dir/storybook-static/backgrounds"
  assert_dir_not_exists "$dir/storybook-static/fonts"
  assert_dir_not_exists "$dir/storybook-static/icons"
  assert_dir_not_exists "$dir/storybook-static/images"
  assert_dir_not_exists "$dir/storybook-static/logos"
  assert_dir_not_exists "$dir/storybook-static/videos"
  assert_dir_exists "$dir/storybook-static/api"
}

@test "Civic Theme assets" {
  dir="docroot/themes/contrib/civic"

  # Files managed by the developer.
  assert_dir_exists "$dir/assets"
  assert_dir_exists "$dir/assets/backgrounds"
  assert_dir_exists "$dir/assets/fonts"
  assert_dir_not_exists "$dir/assets/icons"
  assert_dir_exists "$dir/assets/logos"
  assert_dir_exists "$dir/assets/images"
  assert_file_exists "$dir/assets/images/demo.png"
  assert_dir_exists "$dir/assets/videos"
  assert_file_exists "$dir/assets/videos/demo.avi"
  assert_file_exists "$dir/assets/videos/demo.mp4"
  assert_file_exists "$dir/assets/videos/demo.webm"
  assert_file_exists "$dir/assets/videos/demo_poster.png"
  assert_file_exists "$dir/assets/favicon.ico"

  # Files managed by distribution build.
  assert_dir_exists "$dir/dist"
  assert_file_exists "$dir/dist/civic.css"
  assert_file_exists "$dir/dist/civic.js"
  assert_dir_exists "$dir/dist/assets"
  assert_dir_exists "$dir/dist/assets/backgrounds"
  assert_dir_not_exists "$dir/dist/assets/fonts"
  assert_dir_not_exists "$dir/dist/assets/icons"
  assert_dir_exists "$dir/dist/assets/images"
  assert_dir_exists "$dir/dist/assets/logos"
  assert_dir_exists "$dir/dist/assets/videos"
  assert_dir_not_exists "$dir/dist/assets/api"

  # Files managed by static Storybook build.
  assert_dir_exists "$dir/storybook-static"
  assert_file_exists "$dir/storybook-static/index.html"
  assert_file_exists "$dir/storybook-static/iframe.html"
  assert_dir_exists "$dir/storybook-static/assets"
  assert_dir_exists "$dir/storybook-static/assets/backgrounds"
  assert_dir_not_exists "$dir/storybook-static/assets/fonts"
  assert_dir_not_exists "$dir/storybook-static/assets/icons"
  assert_dir_exists "$dir/storybook-static/assets/images"
  assert_dir_exists "$dir/storybook-static/assets/logos"
  assert_dir_not_exists "$dir/storybook-static/backgrounds"
  assert_dir_not_exists "$dir/storybook-static/fonts"
  assert_dir_not_exists "$dir/storybook-static/icons"
  assert_dir_not_exists "$dir/storybook-static/images"
  assert_dir_not_exists "$dir/storybook-static/logos"
  assert_dir_not_exists "$dir/storybook-static/videos"
  assert_dir_exists "$dir/storybook-static/api"
}

@test "Civic Demo Theme assets" {
  dir="docroot/themes/custom/civic_demo"

  # Files managed by the developer.
  assert_dir_exists "$dir/assets"
  assert_dir_exists "$dir/assets/backgrounds"
  assert_dir_exists "$dir/assets/fonts"
  assert_dir_exists "$dir/assets/icons"
  assert_dir_exists "$dir/assets/logos"
  assert_dir_exists "$dir/assets/images"
  assert_file_exists "$dir/assets/images/demo.png"
  assert_dir_exists "$dir/assets/videos"
  assert_file_exists "$dir/assets/videos/demo.avi"
  assert_file_exists "$dir/assets/videos/demo.mp4"
  assert_file_exists "$dir/assets/videos/demo.webm"
  assert_file_exists "$dir/assets/videos/demo_poster.png"
  assert_file_exists "$dir/assets/favicon.ico"

  # Files managed by distribution build.
  assert_dir_exists "$dir/dist"
  assert_file_exists "$dir/dist/styles.css"
  assert_file_exists "$dir/dist/scripts.js"
  assert_dir_exists "$dir/dist/assets"
  assert_dir_exists "$dir/dist/assets/backgrounds"
  assert_dir_exists "$dir/dist/assets/fonts"
  assert_dir_exists "$dir/dist/assets/icons"
  assert_dir_exists "$dir/dist/assets/images"
  assert_dir_exists "$dir/dist/assets/logos"
  assert_dir_exists "$dir/dist/assets/videos"
  assert_dir_not_exists "$dir/dist/assets/api"

  # Files managed by static Storybook build.
  assert_dir_exists "$dir/storybook-static"
  assert_file_exists "$dir/storybook-static/index.html"
  assert_file_exists "$dir/storybook-static/iframe.html"
  assert_dir_exists "$dir/storybook-static/assets"
  assert_dir_exists "$dir/storybook-static/assets/backgrounds"
  assert_dir_exists "$dir/storybook-static/assets/fonts"
  assert_dir_exists "$dir/storybook-static/assets/icons"
  assert_dir_exists "$dir/storybook-static/assets/images"
  assert_dir_exists "$dir/storybook-static/assets/logos"
  assert_dir_exists "$dir/storybook-static/assets/videos"
  assert_dir_not_exists "$dir/storybook-static/backgrounds"
  assert_dir_not_exists "$dir/storybook-static/fonts"
  assert_dir_not_exists "$dir/storybook-static/icons"
  assert_dir_not_exists "$dir/storybook-static/images"
  assert_dir_not_exists "$dir/storybook-static/logos"
  assert_dir_not_exists "$dir/storybook-static/videos"
  assert_dir_exists "$dir/storybook-static/api"
}
