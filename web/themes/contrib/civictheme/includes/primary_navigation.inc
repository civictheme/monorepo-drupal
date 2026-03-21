<?php

/**
 * @file
 * Primary navigation block theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Preprocess for primary navigation menu block.
 *
 * Called from civictheme_preprocess_block__menu_block().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_block__menu_block__civictheme_primary_navigation(array &$variables): void {
  $theme = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $items = $variables['content']['#items'] ?? [];
  $title = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  $dropdown = civictheme_get_theme_config_manager()->load('components.navigation.primary_navigation.dropdown', CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER);
  $dropdown_columns = (int) civictheme_get_theme_config_manager()->load('components.navigation.primary_navigation.dropdown_columns', 4);
  $dropdown_columns_fill = (bool) civictheme_get_theme_config_manager()->load('components.navigation.primary_navigation.dropdown_columns_fill', FALSE);
  $is_animated = (bool) civictheme_get_theme_config_manager()->load('components.navigation.primary_navigation.is_animated', FALSE);
  $modifier_class = civictheme_extract_modifier_class($variables);
  $modifier_class = trim('ct-primary-navigation ' . ($modifier_class ?? ''));

  $expand_all_items = $variables['elements']['#configuration']['expand_all_items'] ?? FALSE;
  _civictheme_preprocess_menu_items($items, $expand_all_items);

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:navigation',
    '#props' => array_filter([
      'theme' => $theme,
      'name' => 'primary',
      'type' => $dropdown === 'none' ? 'inline' : $dropdown,
      'variant' => 'primary',
      'dropdown_columns' => $dropdown_columns,
      'dropdown_columns_fill' => $dropdown_columns_fill,
      'is_animated' => $is_animated,
      'items' => $items ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
    ]),
  ];
}
