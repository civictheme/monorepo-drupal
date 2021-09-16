<?php

/**
 * @file
 * Banner component alterations.
 */

use Drupal\Core\Cache\Cache;
use Drupal\Core\StringTranslation\TranslatableMarkup;

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
  if ($entity->bundle() != 'civic_banner' || ($entity->hasField('field_b_type') && $entity->field_b_type->isEmpty())) {
    return;
  }

  // Cache tags.
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter( 'node');
  if (!is_null($node)) {
    $variables['#cache']['tags'] = Cache::mergeTags($entity->getCacheTags(), $node->getCacheTags());
  }
  $variables['#cache']['contexts'][] = 'url.path';
  $variables['#cache']['contexts'][] = 'url.query_args';

  $variables['type'] = CIVIC_BANNER_TYPE_DEFAULT;
  $variables['theme'] = CIVIC_BANNER_DEFAULT_THEME;

  // Banner theme.
  if ($entity->hasField('field_b_type') && !$entity->field_b_type->isEmpty()) {
    $variables['theme'] = strtolower($entity->field_b_type->getString());
  }

  _civic_preprocess_civic_banner_title($variables);
  _civic_preprocess_civic_banner_breadcrumb($variables);
  _civic_preprocess_civic_banner_node($variables);

  // @todo preprocess other optional variables for banner.
  $variables['content_top'] = NULL;
  $variables['content_top2'] = NULL;
  $variables['content_top3'] = NULL;
  $variables['content_bottom'] = NULL;
}

/**
 * Preprocess banner title.
 */
function _civic_preprocess_civic_banner_title(&$variables) {
  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $page_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());

  if (!empty($page_title)) {
    $variables['title'] = $page_title;
  }
}

/**
 * Preprocess banner title.
 */
function _civic_preprocess_civic_banner_breadcrumb(&$variables) {
  $route_match = \Drupal::routeMatch();
  $breadcrumb_manager = \Drupal::service('breadcrumb');
  $breadcrumbs = $breadcrumb_manager->build($route_match);
  if ($breadcrumbs) {
    $variables['breadcrumbs'] = $breadcrumbs;
  }
}

/**
 * Preprocess banner for all nodes.
 */
function _civic_preprocess_civic_banner_node(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter( 'node');

  if (is_null($node)) {
    return;
  }

  // Banner title.
  $variables['title'] = $node->getTitle();

  $variables['is_front'] = $is_front = \Drupal::service('path.matcher')->isFrontPage();
  // Banner last updated date.
  if (!$is_front) {
    $variables['content_middle'] = [
      '#type' => 'item',
      '#title' => new TranslatableMarkup('Last updated'),
      '#weight' => -1,
      '#markup' => \Drupal::service('date.formatter')->format($node->getChangedTime(), 'medium'),
    ];
  }

  //Banner background.
  if ($node->hasField('field_n_banner_background') && !$node->field_n_banner_background->isEmpty()) {
    $variables['background'] = \Drupal::entityTypeManager()->getViewBuilder('media')->view($node->field_n_banner_background->entity);
  }

  // Banner type.
  if ($node->hasField('field_n_banner_type') && !$node->field_n_banner_type->isEmpty()) {
    $variables['type'] = $node->field_n_banner_type->value;
  }

  // Banner content components.
  if ($node->hasField('field_n_banner_components') && !$node->field_n_banner_components->isEmpty()) {
    $entities = $node->field_n_banner_components->referencedEntities();
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content'] = $render_controller->viewMultiple($entities, 'default');;
  }
}
