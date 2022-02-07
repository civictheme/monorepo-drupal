<?php

/**
 * @file
 * Banner component alterations.
 */

use Drupal\Core\Cache\Cache;
use Drupal\node\NodeInterface;

/**
 * Defines Small banner style.
 */
define('CIVIC_BANNER_TYPE_DEFAULT', 'default');

/**
 * Defines Large banner style.
 */
define('CIVIC_BANNER_TYPE_LARGE', 'large');

/**
 * Defines Large banner style.
 */
define('CIVIC_BANNER_DEFAULT_THEME', 'light');

/**
 * Civic banner block theme suggestion.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_theme_suggestions_civic_banner(array &$suggestions, array $variables) {
  if (!empty($variables['elements']['#base_plugin_id']) && $variables['elements']['#base_plugin_id'] == 'block_content') {
    $entity = $variables['elements']['content']['#block_content'];
    if ($entity->bundle() == 'civic_banner') {
      $suggestions[] = 'block__' . $entity->bundle() . '__default';
    }
  }
}

/**
 * Preprocess for Programmatic Block - Banner block.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_civic_banner(&$variables) {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civic_banner' || ($entity->hasField('field_c_b_type') && $entity->field_c_b_type->isEmpty())) {
    return;
  }

  $variables['content'] = NULL;

  // Cache tags.
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    $variables['#cache']['tags'] = Cache::mergeTags($entity->getCacheTags(), $node->getCacheTags());
  }
  $variables['#cache']['contexts'][] = 'url.path';
  $variables['#cache']['contexts'][] = 'url.query_args';

  $variables['type'] = CIVIC_BANNER_TYPE_DEFAULT;

  $variables['theme'] = CIVIC_BANNER_DEFAULT_THEME;
  if ($entity->hasField('field_c_b_theme') && !$entity->get('field_c_b_theme')->isEmpty()) {
    $variables['theme'] = strtolower($entity->get('field_c_b_theme')->getString());
  }

  if ($entity->hasField('field_c_b_background_image') && !$entity->get('field_c_b_background_image')->isEmpty()) {
    $variables['background_image'] = civic_media_get_url_from_field($entity, 'field_c_b_background_image');
  }

  _civic_preprocess_civic_banner_title($variables);
  _civic_preprocess_civic_banner_breadcrumb($variables);
  _civic_preprocess_civic_banner_node($variables);

  if ($variables['type'] == CIVIC_BANNER_TYPE_LARGE) {
    $variables['decorative'] = TRUE;
  }

  // Explicitly defined variable to allow additional preprocessing.
  $variables['content_top1'] = NULL;
  $variables['content_top2'] = NULL;
  $variables['content_top3'] = NULL;
  $variables['content_bottom'] = NULL;
}

/**
 * Preprocess banner title.
 */
function _civic_preprocess_civic_banner_title(&$variables) {
  // Banner title.
  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $page_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
  $variables['title'] = $page_title ?? '';
}

/**
 * Preprocess banner breadcrumb.
 */
function _civic_preprocess_civic_banner_breadcrumb(&$variables) {
  $is_front = \Drupal::service('path.matcher')->isFrontPage();

  // Disable breadcrumb on homepage.
  if ($is_front) {
    return;
  }

  $route_match = \Drupal::routeMatch();
  $breadcrumb_manager = \Drupal::service('breadcrumb');
  $breadcrumb = $breadcrumb_manager->build($route_match);
  if ($breadcrumb) {
    $variables['breadcrumb'] = $breadcrumb->toRenderable();
    if (isset($variables['theme'])) {
      // Changing to Civic theme to pass other variables to template.
      $variables['breadcrumb']['#theme'] = 'civic_breadcrumb';
      $variables['breadcrumb']['#display_theme'] = $variables['theme'];
    }
  }
}

/**
 * Preprocess banner for all nodes.
 */
function _civic_preprocess_civic_banner_node(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  $node_revision = \Drupal::routeMatch()->getParameter('node_revision');
  if ($node_revision !== NULL) {
    if ($node_revision instanceof NodeInterface) {
      $node_revision = $node_revision->id();
    }
    $node = node_revision_load($node_revision);
  }
  if (is_null($node)) {
    return;
  }

  if ($node->hasField('field_c_n_banner_type') && !$node->get('field_c_n_banner_type')->isEmpty()) {
    $variables['type'] = $node->get('field_c_n_banner_type')->getString();
  }

  if ($node->hasField('field_c_n_banner_theme') && !$node->get('field_c_n_banner_theme')->isEmpty()) {
    $variables['theme'] = $node->get('field_c_n_banner_theme')->getString();
  }

  $is_front = \Drupal::service('path.matcher')->isFrontPage();
  $variables['is_front'] = $is_front;

  if (!$is_front) {
    $variables['content'][] = [
      '#theme' => 'civic_basic_content',
      '#content' => 'Last updated: ' . \Drupal::service('date.formatter')->format($node->getChangedTime(), 'civic_short_date'),
    ];
  }

  // Banner background.
  if ($node->hasField('field_c_n_banner_background') && !$node->get('field_c_n_banner_background')->isEmpty()) {
    $variables['background_image'] = civic_media_get_url_from_field($node, 'field_c_n_banner_background');
  }

  // Banner featured image.
  if ($node->hasField('field_c_n_banner_featured_image') && !$node->get('field_c_n_banner_featured_image')->isEmpty()) {
    $variables['featured_image']['src'] = civic_media_get_url_from_field($node, 'field_c_n_banner_featured_image');
    // @todo Iplement correct getter for alt attribute.
    $variables['featured_image']['alt'] = t('Banner featured image');
  }

  // Banner content components.
  if ($node->hasField('field_c_n_banner_components') && !$node->get('field_c_n_banner_components')->isEmpty()) {
    $entities = $node->field_c_n_banner_components->referencedEntities();
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content'][] = $render_controller->viewMultiple($entities, 'default');
  }

  // Banner content below components.
  if ($node->hasField('field_c_n_banner_components_bott') && !$node->get('field_c_n_banner_components_bott')->isEmpty()) {
    $entities = $node->field_c_n_banner_components_bott->referencedEntities();
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content_below'][] = $render_controller->viewMultiple($entities, 'default');
  }
}
