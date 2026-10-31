<?php

/**
 * @file
 * Footer related functions.
 */

declare(strict_types=1);

/**
 * Preprocess for footer menu block.
 */
function _civictheme_preprocess_block__footer(array &$variables): void {
  if (($variables['base_plugin_id'] ?? '') !== 'menu_block') {
    return;
  }

  $menu_name = strtr((string) ($variables['elements']['#derivative_plugin_id'] ?? ''), '-', '_');

  // The footer template is selected by the block's suggestion, so it takes
  // precedence over the menu name.
  $suggestion = $variables['elements']['#configuration']['suggestion'] ?? '';
  if ($suggestion === 'civictheme_footer') {
    $menu_name = $suggestion;
  }

  // Remove the block heading from footer menu blocks: the heading is rendered
  // by the navigation component from the 'title' variable set in
  // _civictheme_preprocess_block__navigation().
  if ($menu_name === 'civictheme_footer') {
    $variables['label'] = '';
  }
}
