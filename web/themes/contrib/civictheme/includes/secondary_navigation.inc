<?php

/**
 * @file
 * Secondary navigation block theme alterations.
 */

use Drupal\block\Entity\Block;
use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_block__HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__menu_block__civictheme_secondary_navigation(array &$variables): void {
  $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['items'] = $variables['content']['#items'] ?? [];
  $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

  _civictheme_preprocess_menu_items($variables['items']);

  $block = Block::load($variables['elements']['#id']);
  $region = $block->getRegion();

  if (strpos($region, 'header') === 0) {
    $variables['modifier_class'] = 'ct-justify-content-end';
  }

  if (strpos($region, 'sidebar') === 0) {
    $variables['in_sidebar'] = TRUE;
  }
}
