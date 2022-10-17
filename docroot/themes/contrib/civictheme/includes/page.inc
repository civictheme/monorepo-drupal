<?php

/**
 * @file
 * Page related functions.
 */

use Drupal\civictheme\CivicthemeConstants;

/**
 * Generic page pre-processing.
 */
function _civictheme_preprocess_page(&$variables) {
  // Set header and footer theme from theme settings.
  $variables['page']['header_theme'] = theme_get_setting('components.header.theme') ?? CivicthemeConstants::HEADER_THEME_DEFAULT;
  $variables['page']['footer_theme'] = theme_get_setting('components.footer.theme') ?? CivicthemeConstants::FOOTER_THEME_DEFAULT;
  $variables['page']['footer_background_image'] = theme_get_setting('components.footer.background_image');

  // Always contain non-node page content (unless provided from elsewhere).
  // For nodes this is set to false via _civictheme_preprocess_page_node as
  // layout is controlled via layout templates.
  $variables['page']['content_contained'] = $variables['page']['content_contained'] ?? TRUE;
}
