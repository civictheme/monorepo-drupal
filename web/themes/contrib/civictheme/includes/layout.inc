<?php

/**
 * @file
 * Layout related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\Plugin\Layout\ThreeColumnsLayout;

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_layout__three_columns(array &$variables): void {
  $variables['is_contained'] = $variables['settings']['is_contained'] ?? FALSE;
  $variables['vertical_spacing'] = $variables['settings']['vertical_spacing'] ?? 'auto';

  $defaults = ThreeColumnsLayout::MOBILE_STACK_ORDER_DEFAULTS;
  $mobile_stack_order_enabled = !empty($variables['settings']['mobile_stack_order_enabled']);

  // Only pass custom order to the template when the feature is enabled and
  // the order differs from defaults.
  // When using defaults, no inline styles are needed and the SCSS handles
  // ordering for empty regions via --no-* class combinations. When custom
  // order is set, inline styles override all 5 CSS custom properties;
  // empty regions still get a value but it has no effect since the template
  // does not render their HTML element.
  if (!$mobile_stack_order_enabled) {
    $variables['mobile_stack_order'] = [];
    return;
  }

  $mobile_stack_order = $variables['settings']['mobile_stack_order'] ?? $defaults;

  if ($mobile_stack_order == $defaults) {
    $variables['mobile_stack_order'] = [];
    return;
  }

  $variables['mobile_stack_order'] = array_map('intval', $mobile_stack_order);
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_block__layout_builder(array &$variables): void {
  $variables['modifier_class'] = FALSE;
}
