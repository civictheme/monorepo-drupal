<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Utility;

use Drupal\Core\Template\Attribute;

/**
 * Attribute and modifier class helpers.
 *
 * The procedural civictheme_*_modifier_class() functions delegate here.
 */
final class CivicthemeAttribute {

  /**
   * Add modifier class to the variables array, preserving existing classes.
   *
   * @param array $variables
   *   Variables array.
   * @param string|array $classes
   *   Classes to add.
   */
  public static function addModifierClass(array &$variables, string|array $classes): void {
    $existing = $variables['modifier_class'] ?? '';
    if ($existing === FALSE) {
      return;
    }
    $classes = is_array($classes) ? $classes : [$classes];
    $existing_classes = explode(' ', $existing);
    $new_classes = array_merge($existing_classes, $classes);
    $variables['modifier_class'] = trim(implode(' ', array_unique($new_classes)));
  }

  /**
   * Remove modifier class from the variables array, preserving other classes.
   *
   * @param array $variables
   *   Variables array.
   * @param string|array $classes
   *   Classes to remove.
   */
  public static function removeModifierClass(array &$variables, string|array $classes): void {
    if (!isset($variables['modifier_class']) || $variables['modifier_class'] === FALSE) {
      return;
    }
    $classes = is_array($classes) ? $classes : [$classes];
    $existing_classes = explode(' ', $variables['modifier_class']);
    $remaining_classes = array_diff($existing_classes, $classes);
    $variables['modifier_class'] = trim(implode(' ', $remaining_classes));
  }

  /**
   * Preprocess 'attribute' classes variables into 'modifier_class'.
   *
   * Preprocessors can set $variables['modifier_class'] = FALSE to disable
   * this conversion.
   */
  public static function convertAttributesToModifierClass(array &$variables): void {
    // Allow to skip this conversion.
    if (isset($variables['modifier_class']) && $variables['modifier_class'] === FALSE) {
      return;
    }

    if (!isset($variables['attributes']['class'])) {
      return;
    }

    $attributes = new Attribute($variables['attributes']);
    self::addModifierClass($variables, $attributes->getClass()->value());

    // Remove class from attributes to avoid duplicated 'class' attribute on
    // the element.
    unset($variables['attributes']['class']);
    $variables['attributes'] = new Attribute($variables['attributes']);
  }

}
