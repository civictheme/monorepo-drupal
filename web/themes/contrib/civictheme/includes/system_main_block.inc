<?php

/**
 * @file
 * Block content related functions.
 */

declare(strict_types=1);

/**
 * Preprocess for system_main_block block.
 */
function _civictheme_preprocess_block__system_main_block(array &$variables): void {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'system_main_block') {
    return;
  }

  // Add the most generic class to make text appear styled.
  $variables['attributes']['class'][] = 'ct-text-regular';
  $variables['modifier_class'] = FALSE;
}
