<?php

/**
 * @file
 * Page related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Generic page pre-processing.
 */
function _civictheme_preprocess_page(array &$variables): void {
  // Set header and footer theme from theme settings.
  $variables['page']['header_theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['page']['footer_theme'] = civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
  $variables['page']['footer_background_image'] = civictheme_get_theme_config_manager()->load('components.footer.background_image.path');

  // Always contain non-node page content (unless provided from elsewhere).
  // For nodes this is set to false via _civictheme_preprocess_page_node as
  // layout is controlled via layout templates.
  $variables['page']['content_contained'] = $variables['page']['content_contained'] ?? TRUE;
}
