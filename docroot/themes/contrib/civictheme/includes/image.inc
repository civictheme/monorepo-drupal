<?php

/**
 * @file
 * Image related functions.
 */

/**
 * Generic image pre-processing.
 */
function _civictheme_preprocess_image(&$variables) {
  if (isset($variables['attributes']['src'])) {
    $variables['src'] = $variables['attributes']['src'];
  }
  elseif (!empty($variables['uri'])) {
    $variables['attributes']['src'] = file_url_transform_relative(\Drupal::service('file_url_generator')->generateAbsoluteString($variables['uri']));
  }
}
