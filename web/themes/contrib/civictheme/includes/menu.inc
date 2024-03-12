<?php

/**
 * @file
 * Menu related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Preprocess for footer menu block.
 */
function _civictheme_preprocess_block__civictheme_footer_menu(array &$variables): void {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');
  if ($menu_name == 'civictheme_footer') {
    // Set menu theme as per footer theme.
    $variables['theme'] = civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

    _civictheme_preprocess_menu_items($variables['items']);
  }
}

/**
 * Add required element for external link.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function _civictheme_preprocess_menu_items(array &$items, bool $expand_all_items = FALSE): void {
  foreach ($items as &$item) {
    $item += [
      'is_new_window' => $item['url']->isExternal(),
      'is_external' => $item['url']->isExternal(),
    ];

    if (empty($item['original_link']->getPluginDefinition()['expanded']) && !$expand_all_items) {
      $item['below'] = [];
      $item['is_expanded'] = FALSE;
    }

    if ($expand_all_items) {
      $item['is_expanded'] = TRUE;
    }

    if (count($item['below']) > 0) {
      _civictheme_preprocess_menu_items($item['below'], $expand_all_items);
    }
  }
}

/**
 * Preprocess for primary navigation menu block.
 */
function _civictheme_preprocess_block__navigation(array &$variables): void {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');

  if ($menu_name == 'civictheme_primary_navigation' || $menu_name == 'civictheme_secondary_navigation') {
    $key = substr($menu_name, strlen('civictheme_'));
    // Set menu theme as per footer theme.
    $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['dropdown'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown', $key), $menu_name == 'civictheme_primary_navigation' ? CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER : CivicthemeConstants::NAVIGATION_DROPDOWN_DROPDOWN);
    $variables['dropdown_columns'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown_columns', $key), 4);
    $variables['dropdown_columns_fill'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown_columns_fill', $key), FALSE);
    $variables['is_animated'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.is_animated', $key), FALSE);
    $expand_all_items = $variables['elements']['#configuration']['expand_all_items'] ?? FALSE;
    _civictheme_preprocess_menu_items($variables['items'], $expand_all_items);
  }
}
