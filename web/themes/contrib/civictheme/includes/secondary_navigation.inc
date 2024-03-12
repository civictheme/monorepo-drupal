<?php

/**
 * @file
 * Secondary navigation block theme alterations.
 */

declare(strict_types=1);

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

  $block = Block::load($variables['elements']['#id']);
  $region = $block->getRegion();

  if (str_starts_with($region, 'header')) {
    $variables['modifier_class'] = 'ct-justify-content-end';
  }

  if (str_starts_with($region, 'sidebar')) {
    $variables['in_sidebar'] = TRUE;
    $expand_all_items = $variables['configuration']['expand_all_items'] ?? FALSE;
  }

  _civictheme_preprocess_menu_items($variables['items'], $expand_all_items ?? FALSE);
}
