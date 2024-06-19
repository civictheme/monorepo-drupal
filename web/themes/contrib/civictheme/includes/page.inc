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
  $variables['page']['is_contained'] = $variables['page']['content_contained'] ?? TRUE;
  $variables['page']['is_contained'] = $variables['page']['is_contained'] ?? TRUE;
}

/**
 * Generic page pre-process for all view modes of a node.
 */
function _civictheme_preprocess_page__node(array &$variables): void {
  /** @var \Drupal\node\Entity\Node $node */
  $node = civictheme_load_node_or_revision($variables);

  // @phpstan-ignore-next-line
  if (!$node) {
    return;
  }

  // Layout determines whether a node is contained and what it's layout is.
  $variables['page']['content_contained'] = FALSE;
  $variables['page']['is_contained'] = FALSE;

  $variables['vertical_spacing'] = civictheme_get_field_value($node, 'field_c_n_vertical_spacing');

  // @todo Add support for hiding of the right sidebar.
  $variables['hide_sidebar_left'] = civictheme_get_field_value($node, 'field_c_n_hide_sidebar') ?? FALSE;

  if (civictheme_is_layout_builder_enabled($node)) {
    // @todo Add more conditional logic on when to suppress the sidebars.
    $variables['page']['sidebar_top_left'] = [];
    $variables['page']['sidebar_bottom_left'] = [];
    $variables['page']['sidebar_top_right'] = [];
    $variables['page']['sidebar_bottom_right'] = [];
  }
}

