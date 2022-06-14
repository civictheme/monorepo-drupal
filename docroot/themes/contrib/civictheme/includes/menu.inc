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
    $variables['theme'] = theme_get_setting('civictheme_footer_theme') ?? 'dark';
    $variables['items'] = $variables['content']['#items'] ?? [];
    $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';

    _civictheme_preprocess_menu_items($variables['items']);

    // Make all items collapsible.
    $variables['attributes']['data-collapsible'] = '';
    $variables['attributes']['data-collapsible-collapsed'] = '';
    $variables['attributes']['data-responsive'] = '<m';
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
