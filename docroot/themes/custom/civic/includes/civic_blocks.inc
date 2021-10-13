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
  $variables['logos'] = [];
  if (!empty($variables['elements']['#configuration']['use_site_logo'])) {
    $alt_attribute = theme_get_setting('civic_site_logo_alt') ?? '';

    $block = Block::load($variables['elements']['#id']);
    $region = $block->getRegion();
    // Use footer logo if located in a footer region.
    if (strpos($region, 'footer')) {
      $desktop_logo = theme_get_setting('civic_footer_logo_desktop') ?? '';
      $mobile_logo = theme_get_setting('civic_footer_logo_mobile') ?? '';
    }
    else {
      $desktop_logo = theme_get_setting('logo')['url'] ?? '';
      $mobile_logo = theme_get_setting('civic_header_logo_mobile') ?? '';
    }

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
}
