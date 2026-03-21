<?php

/**
 * @file
 * Banner component alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Link;

/**
 * Implements template_preprocess_block__HOOK() for Banner block.
 *
 * 3 parts:
 * 1. Properties from the block.
 * 2. Properties from the node that may override properties from the block.
 * 3. Build SDC render array.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function civictheme_preprocess_block__civictheme_banner(array &$variables): void {
  $block = $variables['elements']['content']['#block_content'] ?? NULL;
  if (!$block || ($block->hasField('field_c_b_type') && $block->field_c_b_type->isEmpty())) {
    return;
  }

  // Explicitly defined variables to allow additional preprocessing by
  // sub-themes. These are collected into slots at the end.
  $variables['content_top1'] = NULL;
  $variables['content_top2'] = NULL;
  $variables['content_top3'] = NULL;
  $variables['content_bottom'] = NULL;
  $variables['content_middle'] = NULL;
  $variables['content_below'] = NULL;
  $variables['site_section'] = NULL;

  $variables['theme'] = civictheme_get_field_value($block, 'field_c_b_theme', TRUE, CivicthemeConstants::BANNER_THEME_DEFAULT);
  $variables['banner_type'] = civictheme_get_field_value($block, 'field_c_b_banner_type', TRUE, CivicthemeConstants::BANNER_TYPE_DEFAULT);

  // Remove existing content so we can build an SDC render array.
  $variables['banner_content'] = NULL;

  $variables['background_image'] = NULL;
  $variables['background_image_blend_mode'] = NULL;
  $background_image = civictheme_get_field_value($block, 'field_c_b_background_image', TRUE, build: $variables);
  if (!empty($background_image)) {
    $variables['background_image'] = civictheme_media_image_get_variables($background_image);
    $variables['background_image_blend_mode'] = civictheme_get_field_value($block, 'field_c_b_banner_blend_mode', TRUE);
  }

  $variables['featured_image'] = NULL;
  $featured_image = civictheme_get_field_value($block, 'field_c_b_featured_image', TRUE, build: $variables);
  if (!empty($featured_image)) {
    $variables['featured_image'] = civictheme_media_image_get_variables($featured_image);
  }

  $title = \Drupal::service('title_resolver')->getTitle(\Drupal::request(), \Drupal::routeMatch()->getRouteObject());
  $title = (string) (is_array($title) ? reset($title) : ((string) $title));
  $variables['title'] = [
    '#type' => 'markup',
    '#markup' => $title,
  ];

  $variables['breadcrumb'] = NULL;
  _civictheme_preprocess_block__civictheme_banner__breadcrumb($variables);

  // Per-node overrides.
  _civictheme_preprocess_block__civictheme_banner__node($variables);

  $variables['is_decorative'] = $variables['banner_type'] == CivicthemeConstants::BANNER_TYPE_LARGE;

  // Cache contexts.
  $variables['#cache']['contexts'][] = 'url.path';
  $variables['#cache']['contexts'][] = 'url.query_args';

  // Build SDC render array from accumulated variables.
  $modifier_class = civictheme_extract_modifier_class($variables);

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:banner',
    '#props' => array_filter([
      'theme' => $variables['theme'],
      'is_decorative' => $variables['is_decorative'] ?? NULL,
      'background_image' => $variables['background_image'],
      'background_image_blend_mode' => $variables['background_image_blend_mode'],
      'featured_image' => $variables['featured_image'],
      'breadcrumb' => $variables['breadcrumb'],
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $variables['title'],
      'site_section' => $variables['site_section'] ? ['#markup' => $variables['site_section']] : NULL,
      'content_top1' => $variables['content_top1'],
      'content_top2' => $variables['content_top2'],
      'content_top3' => $variables['content_top3'],
      'content_middle' => $variables['content_middle'],
      'content' => $variables['banner_content'],
      'content_bottom' => $variables['content_bottom'],
      'content_below' => $variables['content_below'],
    ]),
  ];
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
  $variables['banner_type'] = $type == CivicthemeConstants::INHERIT ? ($variables['banner_type'] ?? CivicthemeConstants::BANNER_TYPE_DEFAULT) : $type;

  $theme = civictheme_get_field_value($node, 'field_c_n_banner_theme', TRUE, CivicthemeConstants::INHERIT);
  $variables['theme'] = $theme == CivicthemeConstants::INHERIT ? ($variables['theme'] ?? CivicthemeConstants::BANNER_THEME_DEFAULT) : $theme;

  $background_image = civictheme_get_field_value($node, 'field_c_n_banner_background', TRUE);
  if (!empty($background_image)) {
    $variables['background_image'] = civictheme_media_image_get_variables($background_image);
    $variables['background_image_blend_mode'] = civictheme_get_field_value($node, 'field_c_n_banner_blend_mode', TRUE);
  }

  $featured_image = civictheme_get_field_value($node, 'field_c_n_banner_featured_image', TRUE, build: $variables);
  if (!empty($featured_image)) {
    $variables['featured_image'] = civictheme_media_image_get_variables($featured_image);
  }

  $variables['title'] = civictheme_get_field_value($node, 'field_c_n_banner_title') ?: $variables['title'];
  if (isset($variables['title']['#markup']) && empty($variables['title']['#markup'])) {
    $variables['title']['#markup'] = $node->getTitle();
  }
  if (civictheme_get_field_value($node, 'field_c_n_banner_hide_breadcrumb', FALSE, FALSE)) {
    $variables['breadcrumb'] = NULL;
  }

  $site_section = civictheme_get_field_value($node, 'field_c_n_site_section', TRUE, build: $variables);
  if (!empty($site_section)) {
    $variables['site_section'] = $site_section->getName();
  }

  $updated_date = _civictheme_node_get_updated_date($node);
  if (!empty($updated_date)) {
    $variables['content_middle'][] = [
      '#theme' => 'civictheme_basic_content',
      '#component_theme' => $variables['theme'] ?? NULL,
      '#content' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => ['class' => ['ct-banner__last_updated']],
        '#value' => 'Last updated: ' . $updated_date,
      ],
    ];
  }

  $banner_components = civictheme_get_field_value($node, 'field_c_n_banner_components');
  if (!empty($banner_components)) {
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['banner_content'][] = $render_controller->viewMultiple($banner_components, 'default');
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
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_preprocess_block__civictheme_banner__breadcrumb(array &$variables): void {
  // Disable breadcrumb on homepage.
  if (\Drupal::service('path.matcher')->isFrontPage()) {
    return;
  }
  /** @var \Drupal\Core\Breadcrumb\Breadcrumb $breadcrumb */
  $breadcrumb = \Drupal::service('breadcrumb')->build(\Drupal::getContainer()->get('current_route_match'));

  // Always show the last item as a plain text (not a link).
  $variables['breadcrumb']['active_is_link'] = FALSE;

  // Gather existing links. Note that some profiles (GovCMS) may already have
  // the functionality to add current page as a last item implemented.
  $renderer = \Drupal::service('renderer');
  foreach ($breadcrumb->getLinks() as $link) {
    $link_text = $link->getText();
    if (is_array($link_text)) {
      $link_text = (string) $renderer->renderInIsolation($link_text);
    }
    $variables['breadcrumb']['links'][] = [
      'text' => Xss::filter((string) $link_text),
      'url' => $link->getUrl()->toString(),
    ];
  }

  $current_route_match = \Drupal::routeMatch();
  $title = \Drupal::service('title_resolver')->getTitle(\Drupal::request(), $current_route_match->getRouteObject());
  if (!empty($title)) {
    $title = (string) (is_array($title) ? reset($title) : ((string) $title));
    $title = [
      '#type' => 'markup',
      '#markup' => $title,
    ];
    $breadcrumb_links = $breadcrumb->getLinks();
    $breadcrumb_last_link = end($breadcrumb_links);
    $link = Link::createFromRoute($title, '<none>');
    $link_text = $link->getText();
    if (is_array($link_text)) {
      $link_text = (string) $renderer->renderInIsolation($link_text);
    }
    // Check if the link already exists and add it if not.
    if ($breadcrumb_last_link instanceof Link && ($breadcrumb_last_link->getText() !== $link_text || $breadcrumb_last_link->getUrl()->toString() !== $link->getUrl()->toString())) {
      $variables['breadcrumb']['links'][] = [
        'text' => Xss::filter((string) $link_text),
        'url' => $link->getUrl()->toString(),
      ];
    }
  }
}
