<?php

/**
 * @file
 * Menu related functions.
 */

/**
 * Preprocess for footer menu block.
 */
function _civictheme_preprocess_block__civictheme_footer_menu(&$variables) {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');
  if ($menu_name == 'civictheme_footer') {
    // Set menu theme as per footer theme.
    $variables['theme'] = theme_get_setting('components.footer.theme') ?? CIVICTHEME_FOOTER_THEME_DEFAULT;
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

    _civictheme_preprocess_menu_items($variables['items']);
  }
}

/**
 * Add required element for external link.
 */
function _civictheme_preprocess_menu_items(&$items) {
  foreach ($items as &$item) {
    $item += [
      'is_new_window' => $item['url']->isExternal(),
      'is_external' => $item['url']->isExternal(),
    ];

    if (count($item['below'])) {
      _civictheme_preprocess_menu_items($item['below']);
    }
  }
}

/**
 * Preprocess for primary navigation menu block.
 */
function _civictheme_preprocess_block__navigation(&$variables) {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');

  if ($menu_name == 'civictheme_primary_navigation' || $menu_name == 'civictheme_secondary_navigation') {
    $key = substr($menu_name, strlen('civictheme_'));
    // Set menu theme as per footer theme.
    $variables['theme'] = theme_get_setting('components.header.theme') ?? CIVICTHEME_HEADER_THEME_DEFAULT;
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['dropdown'] = theme_get_setting("components.navigation.$key.dropdown") ?? CIVICTHEME_NAVIGATION_DROPDOWN_DRAWER;
    $variables['dropdown_columns'] = theme_get_setting("components.navigation.$key.dropdown_columns") ?? FALSE;
    $variables['dropdown_columns_fill'] = theme_get_setting("components.navigation.$key.dropdown_columns_fill") ?? FALSE;
    $variables['is_animated'] = theme_get_setting("components.navigation.$key.is_animated") ?? FALSE;

    _civictheme_preprocess_menu_items($variables['items']);
  }
}
