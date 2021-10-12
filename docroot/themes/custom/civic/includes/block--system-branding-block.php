<?php

/**
 * @file
 * System branding block.
 */

/**
 * Implements template_preprocess_blocK__HOOK().
 */
function civic_preprocess_block__system_branding_block(&$variables) {
  $alt_attribute = theme_get_setting('civic_site_logo_alt');
  $desktop_logo = theme_get_setting('site_logo');
  $mobile_logo = theme_get_setting('civic_header_logo_mobile');
  $variables['logos'] = [
    'mobile' => [
      'src' => $mobile_logo,
      'alt' => $alt_attribute,
    ],
    'desktop' => [
      'src' => $desktop_logo,
      'alt' => $alt_attribute,
    ],
  ];
}
