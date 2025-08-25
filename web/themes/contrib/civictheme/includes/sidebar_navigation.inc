<?php

/**
 * @file
 * Sidebar navigation block theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_block__HOOK() for sidebar navigation.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__menu_block__civictheme_sidebar_navigation(array &$variables): void {
  $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['items'] = $variables['content']['#items'] ?? [];
  $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  $variables['in_sidebar'] = TRUE;
  $expand_all_items = $variables['configuration']['expand_all_items'] ?? FALSE;
  _civictheme_preprocess_menu_items($variables['items'], $expand_all_items);
}
