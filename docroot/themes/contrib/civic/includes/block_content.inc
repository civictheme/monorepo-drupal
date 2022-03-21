<?php

/**
 * @file
 * Block content related functions.
 */

/**
 * Preprocess for block_content block.
 */
function _civic_preprocess_block__content(&$variables) {
  if (isset($variables['elements']['#base_plugin_id']) && $variables['base_plugin_id'] != 'block_content') {
    return;
  }

  switch ($variables['elements']['#configuration']['label']) {
    case 'Copyright':
      $variables['attributes']['class'][] = 'copyright';
      $variables['attributes']['class'][] = 'civic-text-regular';
      break;
  }
}
