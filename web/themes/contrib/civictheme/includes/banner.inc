<?php

/**
 * @file
 * Banner component alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Link;

/**
 * Pre-process for Banner block.
 *
 * 3 parts:
 * 1. Properties from the block.
 * 2. Properties from the node that may override properties from the block.
 * 3. Cache tags.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_preprocess_block__civictheme_banner(array &$variables): void {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $block = $variables['elements']['content']['#block_content'];
  if ($block->bundle() != 'civictheme_banner' || ($block->hasField('field_c_b_type') && $block->field_c_b_type->isEmpty())) {
    return;
  }

  // Explicitly defined variables to allow additional preprocessing.
  $variables['content_top1'] = NULL;
  $variables['content_top2'] = NULL;
  $variables['content_top3'] = NULL;
  $variables['content_bottom'] = NULL;

  $variables['theme'] = civictheme_get_field_value($block, 'field_c_b_theme', TRUE, CivicthemeConstants::BANNER_THEME_DEFAULT);
  $variables['type'] = civictheme_get_field_value($block, 'field_c_b_banner_type', TRUE, CivicthemeConstants::BANNER_TYPE_DEFAULT);

  // Remove existing content.
  $variables['content'] = NULL;

  $background_image = civictheme_get_field_value($block, 'field_c_b_background_image', TRUE);
  if (!empty($background_image)) {
    $variables['background_image'] = civictheme_media_image_get_variables($background_image);
    $variables['background_image_blend_mode'] = civictheme_get_field_value($block, 'field_c_b_banner_blend_mode', TRUE);
  }

  $featured_image = civictheme_get_field_value($block, 'field_c_b_featured_image', TRUE);
  if (!empty($featured_image)) {
    $variables['featured_image'] = civictheme_media_image_get_variables($featured_image);
  }

  $variables['title'] = \Drupal::service('title_resolver')->getTitle(\Drupal::request(), \Drupal::routeMatch()->getRouteObject());

  _civictheme_preprocess_block__civictheme_banner__breadcrumb($variables);

  // Per-node overrides.
  _civictheme_preprocess_block__civictheme_banner__node($variables);

  if ($variables['type'] == CivicthemeConstants::BANNER_TYPE_LARGE) {
    $variables['is_decorative'] = TRUE;
  }

  // Note that per-node overrides above may adjust cache tags as required.
  $variables['#cache']['contexts'][] = 'url.path';
  $variables['#cache']['contexts'][] = 'url.query_args';

  // 'type' is internal field and should not be passed to the template.
  unset($variables['type']);
}

/**
 * Pre-process Banner using per-node overrides.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_preprocess_block__civictheme_banner__node(array &$variables): void {
  $node = \Drupal::routeMatch()->getParameter('node_revision') ?: \Drupal::routeMatch()->getParameter('node');

  if (empty($node)) {
    // Disable cache for the block so that it's not cached without a node for
    // the next page views that could have a node.
    $variables['#cache']['max-age'] = 0;

    return;
  }

  $type = civictheme_get_field_value($node, 'field_c_n_banner_type', TRUE, CivicthemeConstants::INHERIT);
  $variables['type'] = $type == CivicthemeConstants::INHERIT ? ($variables['type'] ?? CivicthemeConstants::BANNER_TYPE_DEFAULT) : $type;

  $theme = civictheme_get_field_value($node, 'field_c_n_banner_theme', TRUE, CivicthemeConstants::INHERIT);
  $variables['theme'] = $theme == CivicthemeConstants::INHERIT ? ($variables['theme'] ?? CivicthemeConstants::BANNER_THEME_DEFAULT) : $theme;

  $background_image = civictheme_get_field_value($node, 'field_c_n_banner_background', TRUE);
  if (!empty($background_image)) {
    $variables['background_image'] = civictheme_media_image_get_variables($background_image);
    $variables['background_image_blend_mode'] = civictheme_get_field_value($node, 'field_c_n_banner_blend_mode', TRUE);
  }

  $featured_image = civictheme_get_field_value($node, 'field_c_n_banner_featured_image', TRUE);
  if (!empty($featured_image)) {
    $variables['featured_image'] = civictheme_media_image_get_variables($featured_image);
  }

  $variables['title'] = civictheme_get_field_value($node, 'field_c_n_banner_title') ?: $variables['title'];

  if (civictheme_get_field_value($node, 'field_c_n_banner_hide_breadcrumb', FALSE, FALSE)) {
    $variables['breadcrumb'] = NULL;
  }

  $site_section = civictheme_get_field_value($node, 'field_c_n_site_section', TRUE);
  if (!empty($site_section)) {
    $variables['site_section'] = $site_section->getName();
  }

  $updated_date = _civictheme_node_get_updated_date($node);
  if (!empty($updated_date)) {
    $variables['content_middle'][] = [
      '#theme' => 'civictheme_basic_content',
      '#component_theme' => $variables['theme'] ?? NULL,
      '#content' => '<p class="ct-banner__last_updated">Last updated: ' . $updated_date . '</p>',
    ];
  }

  $banner_components = civictheme_get_field_value($node, 'field_c_n_banner_components');
  if (!empty($banner_components)) {
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content'][] = $render_controller->viewMultiple($banner_components, 'default');
  }

  $banner_below_components = civictheme_get_field_value($node, 'field_c_n_banner_components_bott');
  if (!empty($banner_below_components)) {
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content_below'][] = $render_controller->viewMultiple($banner_below_components, 'default');
  }

  $block = $variables['elements']['content']['#block_content'];
  if (!empty($block)) {
    $variables['#cache']['tags'] = Cache::mergeTags($block->getCacheTags(), $node->getCacheTags());
  }
}

/**
 * Pre-process for Breadcrumb.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_block__civictheme_banner__breadcrumb(array &$variables): void {
  // Disable breadcrumb on homepage.
  if (\Drupal::service('path.matcher')->isFrontPage()) {
    return;
  }

  $breadcrumb = \Drupal::service('breadcrumb')->build(\Drupal::getContainer()->get('current_route_match'));

  // Always show the last item as a plain text (not a link).
  $variables['breadcrumb']['active_is_link'] = FALSE;

  // Gather existing links. Note that some profiles (GovCMS) may already have
  // the functionality to add current page as a last item implemented.
  foreach ($breadcrumb->getLinks() as $link) {
    $variables['breadcrumb']['links'][] = [
      'text' => $link->getText(),
      'url' => $link->getUrl()->toString(),
    ];
  }

  $current_route_match = \Drupal::routeMatch();
  $title = \Drupal::service('title_resolver')->getTitle(\Drupal::request(), $current_route_match->getRouteObject());
  if (!empty($title)) {
    $title = (string) (is_array($title) ? reset($title) : $title);
    $breadcrumb_links = $breadcrumb->getLinks();
    $breadcrumb_last_link = end($breadcrumb_links);
    $link = Link::createFromRoute($title, '<none>');
    // Check if the link already exists and add it if not.
    if ($breadcrumb_last_link instanceof Link && ($breadcrumb_last_link->getText() != $link->getText() || $breadcrumb_last_link->getUrl() != $breadcrumb_last_link->getUrl())) {
      $variables['breadcrumb']['links'][] = [
        'text' => $link->getText(),
        'url' => $link->getUrl()->toString(),
      ];
    }
  }
}
