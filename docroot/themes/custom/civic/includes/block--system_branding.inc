<?php

/**
 * @file
 * Block theme alterations.
 */

use Drupal\block\Entity\Block;

/**
 * Implements template_preprocess_block__HOOK().
 */
function civic_preprocess_block__system_branding_block(&$variables) {
  $variables['show_site_logo'] = !empty($variables['elements']['#configuration']['use_site_logo']);
  $variables['show_site_name'] = !empty($variables['elements']['#configuration']['use_site_name']);
  $variables['show_site_slogan'] = !empty($variables['elements']['#configuration']['use_site_slogan']);
  if ($variables['show_site_logo']) {
    $variables['show_logo'] = TRUE;
    $alt_attribute = theme_get_setting('civic_site_logo_alt') ?? '';

    $block = Block::load($variables['elements']['#id']);
    $region = $block->getRegion();

    // Use footer logo if it is located in on of the footer regions.
    if (strpos($region, 'footer') === 0) {
      $desktop_logo = theme_get_setting('civic_footer_logo_desktop') ?? '';
      $mobile_logo = theme_get_setting('civic_footer_logo_mobile') ?? '';
    }
    else {
      $variables['modifier_class'] = 'civic-header__logo';
      $desktop_logo = theme_get_setting('logo')['url'] ?? '';
      $mobile_logo = theme_get_setting('civic_header_logo_mobile') ?? '';
    }

    if (!empty($mobile_logo)) {
      $variables['logos']['mobile'] = [
        'src' => '/' . ltrim($mobile_logo, '/'),
        'alt' => $alt_attribute,
      ];
    }

    if (!empty($desktop_logo)) {
      $variables['logos']['desktop'] = [
        'src' => '/' . ltrim($desktop_logo, '/'),
        'alt' => $alt_attribute,
      ];
    }
  }
}
