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
  $theme = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $items = $variables['content']['#items'] ?? [];
  $title = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  $modifier_class = civictheme_extract_modifier_class($variables);
  $modifier_class = trim('ct-sidebar-navigation ' . ($modifier_class ?? ''));

  $expand_all_items = $variables['configuration']['expand_all_items'] ?? FALSE;
  _civictheme_preprocess_menu_items($items, $expand_all_items);

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:side-navigation',
    '#props' => array_filter([
      'theme' => $theme,
      'items' => $items ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
    ]),
  ];
}
