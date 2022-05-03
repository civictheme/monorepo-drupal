<?php

/**
 * @file
 * Helper functions for CivicTheme Demo sub-theme configuration.
 *
 * These imitate the operations actioned by admin users after a sub-theme is
 * installed.
 */

/**
 * Sets civictheme_demo theme settings and site slogan.
 */
function cs_core_civictheme_demo_update_theme_settings() {
  $logo_header_desktop = '/themes/custom/civictheme_demo/dist/assets/logos/civictheme_demo_logo_desktop_light.png';
  $logo_header_mobile = '/themes/custom/civictheme_demo/dist/assets/logos/civictheme_demo_logo_mobile_light.png';
  $logo_footer_desktop = '/themes/custom/civictheme_demo/dist/assets/logos/civictheme_demo_logo_desktop_dark.png';
  $logo_footer_mobile = '/themes/custom/civictheme_demo/dist/assets/logos/civictheme_demo_logo_mobile_dark.png';

  $favicon = '/themes/custom/civictheme_demo/dist/assets/favicon.ico';

  $config = \Drupal::service('config.factory')->getEditable('civictheme_demo.settings');

  $config->set('logo', [
    'use_default' => FALSE,
    'path' => substr($logo_header_desktop, 1),
    'url' => $logo_header_desktop,
  ]);

  $config->set('logo_path', substr($logo_header_desktop, 1));
  $config->set('civictheme_header_logo_mobile', $logo_header_mobile);
  $config->set('civictheme_footer_logo_desktop', $logo_footer_desktop);
  $config->set('civictheme_footer_logo_mobile', $logo_footer_mobile);

  $config->set('civictheme_site_logo_alt', 'CivicTheme demo logo');

  $config->set('favicon', [
    'use_default' => FALSE,
    'path' => substr($favicon, 1),
    'mimetype' => 'image/vnd.microsoft.icon',
  ]);

  $config->set('civictheme_header_theme', 'light');
  $config->set('civictheme_footer_theme', 'dark');

  $config->save();
}
