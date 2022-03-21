<?php

/**
 * @file
 * Image related functions.
 */

/**
 * Generic image pre-processing.
 */
function _civic_preprocess_image(&$variables) {
  if (isset($variables['attributes']['src'])) {
    $variables['src'] = $variables['attributes']['src'];
  }
  elseif (!empty($variables['uri'])) {
    $variables['attributes']['src'] = file_url_transform_relative(file_create_url($variables['uri']));
  }
}
