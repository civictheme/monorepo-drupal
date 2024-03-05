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

  $node = \Drupal::routeMatch()->getParameter('node');
  $block = $variables['elements']['content']['#block_content'];

  // If the node is not loaded, adjust the cache max-age of the
  // banner block to -1.
  if ($block && $block->bundle() == 'civictheme_banner' && !$node) {
    $variables['#cache']['max-age'] = 0;
  }

}
