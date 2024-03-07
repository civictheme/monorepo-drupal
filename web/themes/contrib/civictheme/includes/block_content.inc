<?php

/**
 * @file
 * Block content related functions.
 */

declare(strict_types=1);

/**
 * Preprocess for block_content block.
 */
function _civictheme_preprocess_block__content(array &$variables): void {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'block_content') {
    return;
  }

  if ($variables['elements']['#configuration']['label'] === 'Copyright') {
    $variables['attributes']['class'][] = 'copyright';
    $variables['attributes']['class'][] = 'ct-text-regular';
  }
}
