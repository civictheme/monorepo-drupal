<?php

/**
 * @file
 * Layout related functions.
 */

declare(strict_types=1);

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_layout__three_columns(array &$variables): void {
  $variables['is_contained'] = $variables['settings']['is_contained'] ?? FALSE;
  $variables['vertical_spacing'] = $variables['settings']['vertical_spacing'] ?? 'auto';
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_block__layout_builder(array &$variables): void {
  $variables['modifier_class'] = FALSE;
}
