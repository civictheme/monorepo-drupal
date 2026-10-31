<?php

/**
 * @file
 * Image related functions.
 */

declare(strict_types=1);

/**
 * Generic image pre-processing.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_image(array &$variables): void {
  if (isset($variables['attributes']['src'])) {
    $variables['url'] = $variables['attributes']['src'];
  }
  elseif (!empty($variables['uri'])) {
    $url_generator = \Drupal::service('file_url_generator');
    $variables['url'] = $url_generator->transformRelative($url_generator->generateAbsoluteString($variables['uri']));
  }
}
