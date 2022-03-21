<?php

/**
 * @file
 * Page related functions.
 */

/**
 * Generic page pre-processing.
 */
function _civic_preprocess_page(&$variables) {
  // Set header and footer theme from theme settings.
  $variables['page']['header_theme'] = theme_get_setting('civic_header_theme') ?? 'light';
  $variables['page']['footer_theme'] = theme_get_setting('civic_footer_theme') ?? 'dark';

  // Always contain non-node page content.
  // For nodes this is set to false via _civic_preprocess_page_node as layout is
  // controlled via layout templates.
  $variables['page']['content_contained'] = TRUE;
}
