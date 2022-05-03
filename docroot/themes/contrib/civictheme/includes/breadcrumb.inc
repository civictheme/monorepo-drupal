<?php

/**
 * @file
 * Breadcrumb-related functions.
 */

/**
 * Pre-process for breadcrumb.
 */
function _civictheme_preprocess_civictheme_breadcrumb(&$variables) {
  if (!empty($variables['display_theme'])) {
    $variables['theme'] = $variables['display_theme'];
  }
}
