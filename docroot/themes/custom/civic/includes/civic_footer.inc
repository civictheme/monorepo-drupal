<?php

/**
 * @file
 * Footer alterations.
 */

/**
 * Civic footer block theme suggestions.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_theme_suggestions_footer_menus(&$suggestions, $variables) {
  if (!empty($variables['elements']['#base_plugin_id']) && $variables['elements']['#base_plugin_id'] == 'system_menu_block') {
    $footer_menus = [
      'civic_demo_civicfootermenu1',
      'civic_demo_civicfootermenu2',
      'civic_demo_civicfootermenu3',
      'civic_demo_civicfootermenu4',
      'civicfootermenu1',
      'civicfootermenu2',
      'civicfootermenu3',
      'civicfootermenu4',
    ];
    if (isset($variables['elements']['#id']) && in_array($variables['elements']['#id'], $footer_menus)) {
      $suggestions[] = 'menu__footer__default';
    }
  }
}

/**
 * Implements hook_preprocess().
 */
function civic_preprocess_menu__footer__default(&$variables, $hook) {
  $variables['theme'] = theme_get_setting('civic_footer_theme') ?? 'light';
  $menu_items = !empty($variables['elements']['content']['#items']) ? array_pop($variables['elements']['content']['#items']) : [];

  if (!empty($menu_items)) {
    $variables['title'] = $menu_items['title'];
    $variables['links'] = $menu_items['below'];
  }
  else {
    $variables['title'] = NULL;
    $variables['links'] = [];
  }
}
