<?php

/**
 * @file
 * Menu related functions.
 */

/**
 * Preprocess for footer menu block.
 */
function _civic_preprocess_block__civic_footer_menu(&$variables) {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'menu_block') {
    return;
  }

  $menu_name = strtr($variables['elements']['#derivative_plugin_id'], '-', '_');
  if ($menu_name == 'civic-footer') {
    // Set menu theme as per footer theme.
    $variables['theme'] = theme_get_setting('civic_footer_theme') ?? 'dark';

    // Make all items collapsible.
    $variables['attributes']['data-collapsible'] = '';
    $variables['attributes']['data-collapsible-collapsed'] = '';
    $variables['attributes']['data-responsive'] = '<m';
  }
}
