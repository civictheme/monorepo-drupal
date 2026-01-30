<?php

/**
 * @file
 * Menu related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Url;

/**
 * Add required element for external link.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_menu_items(array &$items, bool $expand_all = FALSE): void {
  foreach ($items as &$item) {
    $item += [
      'is_new_window' => $item['url'] instanceof Url ? $item['url']->isExternal() : FALSE,
      'is_external' => $item['url'] instanceof Url ? $item['url']->isExternal() : FALSE,
    ];

    // Set as expanded based on $expand_all or item's own value or FALSE.
    $item['is_expanded'] = $expand_all || ($item['is_expanded'] ?? FALSE);
    // Initialize 'below' as an empty array if not set.
    $item['below'] = $item['below'] ?? [];
    $item['title'] = isset($item['title']) ? strip_tags($item['title']) : '';

    if (!empty($item['below'])) {
      _civictheme_preprocess_menu_items($item['below'], $expand_all);
    }
  }
}

/**
 * Preprocess for primary navigation menu block.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_preprocess_block__navigation(array &$variables): void {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');

  $menu_theme_list = [
    'civictheme_primary_navigation',
    'civictheme_secondary_navigation',
    'civictheme_sidebar_navigation',
    'civictheme_footer',
  ];

  if (!empty($variables['elements']['#configuration']['suggestion']) && in_array($variables['elements']['#configuration']['suggestion'], $menu_theme_list)) {
    $menu_name = $variables['elements']['#configuration']['suggestion'];
  }

  if ($menu_name === 'civictheme_primary_navigation' || $menu_name === 'civictheme_secondary_navigation') {
    $key = substr($menu_name, strlen('civictheme_'));
    // Set menu theme as per footer theme.
    $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
    // Set name for aria-label of <nav> element.
    $variables['name'] = strtr($key, '_navigation', '');
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['dropdown'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown', $key), $menu_name === 'civictheme_primary_navigation' ? CivicthemeConstants::NAVIGATION_DROPDOWN_DRAWER : CivicthemeConstants::NAVIGATION_DROPDOWN_DROPDOWN);
    $variables['dropdown_columns'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown_columns', $key), 4);
    $variables['dropdown_columns_fill'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.dropdown_columns_fill', $key), FALSE);
    $variables['is_animated'] = civictheme_get_theme_config_manager()->load(sprintf('components.navigation.%s.is_animated', $key), FALSE);
    $variables['variant'] = $menu_name === 'civictheme_primary_navigation' ? 'primary' : 'secondary';
    $expand_all_items = $variables['elements']['#configuration']['expand_all_items'] ?? FALSE;
    _civictheme_preprocess_menu_items($variables['items'], $expand_all_items);
  }

  if ($menu_name === 'civictheme_footer') {
    // Set menu theme as per footer theme.
    $variables['theme'] = civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

    _civictheme_preprocess_menu_items($variables['items']);
  }
}
