<?php

/**
 * @file
 * Breadcrumb-related functions.
 */

/**
 * Pre-process for breadcrumb.
 */
function _civic_preprocess_civic_breadcrumb(&$variables) {
  if (!empty($variables['display_theme'])) {
    $variables['theme'] = $variables['display_theme'];
  }
}
