<?php

/**
 * @file
 * Page related functions.
 */

/**
 * Generic page pre-processing.
 */
function _civictheme_preprocess_page(&$variables) {
  // Set header and footer theme from theme settings.
  $variables['page']['header_theme'] = theme_get_setting('components.header.theme') ?? CIVICTHEME_HEADER_THEME_DEFAULT;
  $variables['page']['footer_theme'] = theme_get_setting('components.footer.theme') ?? CIVICTHEME_FOOTER_THEME_DEFAULT;

  // Always contain non-node page content.
  // For nodes this is set to false via _civictheme_preprocess_page_node as
  // layout is controlled via layout templates.
  $variables['page']['content_contained'] = TRUE;
}
