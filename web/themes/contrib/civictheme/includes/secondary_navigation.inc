<?php

/**
 * @file
 * Secondary navigation block theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_block__HOOK() for header secondary navigation.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__menu_block__civictheme_secondary_navigation(array &$variables): void {
  $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['items'] = $variables['content']['#items'] ?? [];
  $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  civictheme_add_modifier_class($variables, 'ct-flex-justify-content-end');
  _civictheme_preprocess_menu_items($variables['items'], FALSE);
}
