<?php

/**
 * @file
 * Block related functions.
 */

declare(strict_types=1);

use Drupal\Core\Template\Attribute;

/**
 * Preprocess block to add drupal_attributes.
 *
 * Creates a copy of attributes as drupal_attributes for use in templates
 * where Drupal's default block classes need to be preserved separately.
 */
function _civictheme_preprocess_block__drupal(array &$variables): void {
  $attributes = $variables['attributes'] ?? [];

  // Convert to array if it's already an Attribute object.
  if ($attributes instanceof Attribute) {
    $attributes = $attributes->toArray();
  }

  $variables['drupal_attributes'] = new Attribute($attributes);
}
