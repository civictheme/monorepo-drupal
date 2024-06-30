<?php

/**
 * @file
 * Page related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Cache\Cache;

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

/**
 * Generic page pre-process for all view modes of a node.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_page__node(array &$variables): void {
  /** @var \Drupal\node\Entity\Node $node */
  $node = civictheme_load_node_or_revision($variables);

  // @phpstan-ignore-next-line
  if (!$node) {
    return;
  }

  $variables['vertical_spacing'] = civictheme_get_field_value($node, 'field_c_n_vertical_spacing');

  // @todo Add support for hiding of the right sidebar through a node field.
  $variables['hide_sidebar_left'] = civictheme_get_field_value($node, 'field_c_n_hide_sidebar') ?? FALSE;

  $layout_builder_settings_per_view_mode = civictheme_get_layout_builder_settings_per_view_mode($node->getEntityTypeId(), $node->bundle());
  if (!empty($layout_builder_settings_per_view_mode)) {
    // Layout builder determines whether a node is contained and what it's
    // layout is, so we set as not contained to allow layout builder to
    // span edge-to-edge.
    $variables['page']['content_contained'] = FALSE;

    $variables['#cache']['contexts'] = Cache::mergeContexts(
      $variables['#cache']['contexts'] ?? [],
      $node->getCacheContexts(),
      [
        'url.query_args',
        'url.path',
      ]
    );

    $context = [
      'node' => $node,
      'layout_builder_settings_per_view_mode' => $layout_builder_settings_per_view_mode,
    ];

    // Allow to suppress sidebars. Note that implementors should ensure that
    // the cacheability metadata is set correctly.
    \Drupal::moduleHandler()->alter('civictheme_layout_suppress_page_regions', $variables, $context);
    \Drupal::service('theme.manager')->alter('civictheme_layout_suppress_page_regions', $variables, $context);
  }
}

