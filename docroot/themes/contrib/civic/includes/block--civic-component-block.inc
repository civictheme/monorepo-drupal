<?php

/**
 * @file
 * Civic component block theme alterations.
 */

/**
 * Alter theme suggestions for civic component block.
 */
function _civic_theme_suggestions_block_alter__civic_component_block(array &$suggestions, array $variables) {
  if (!empty($variables['elements']['#base_plugin_id']) && $variables['elements']['#base_plugin_id'] == 'block_content') {
    $entity = $variables['elements']['content']['#block_content'];
    if ($entity->bundle() == 'civic_component_block') {
      $suggestions[] = 'block__' . $entity->bundle() . '__default';
    }
  }
}
