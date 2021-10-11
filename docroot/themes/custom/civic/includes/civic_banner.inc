<?php

/**
 * @file
 * Banner component alterations.
 */

use Drupal\Core\Cache\Cache;

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

  // Cache tags.
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if (!is_null($node)) {
    $variables['#cache']['tags'] = Cache::mergeTags($entity->getCacheTags(), $node->getCacheTags());
  }
  $variables['#cache']['contexts'][] = 'url.path';
  $variables['#cache']['contexts'][] = 'url.query_args';

  $variables['type'] = CIVIC_BANNER_TYPE_DEFAULT;
  $variables['theme'] = CIVIC_BANNER_DEFAULT_THEME;

  // Banner theme.
  if ($entity->hasField('field_c_b_theme') && !$entity->get('field_c_b_theme')->isEmpty()) {
    $variables['theme'] = strtolower($entity->get('field_c_b_theme')->getString());
  }

  // Deafult banner background.
  $default_banner_background = theme_get_setting('civic_banner_background_image');
  if (!empty($default_banner_background)) {
    $variables['backgroundImage'] = file_url_transform_relative(file_create_url($default_banner_background));
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
  // Banner title.
  $block_manager = \Drupal::service('plugin.manager.block');
  $plugin_block = $block_manager->createInstance('page_title_block');

  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $page_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());

  if (!empty($page_title)) {
    $plugin_block->setTitle($page_title);
  }
  $variables['content_title'] = $plugin_block->build();
}

/**
 * Preprocess banner breadcrumb.
 */
function _civic_preprocess_civic_banner_breadcrumb(&$variables) {
  $route_match = \Drupal::routeMatch();
  $breadcrumb_manager = \Drupal::service('breadcrumb');
  $breadcrumbs = $breadcrumb_manager->build($route_match);
  if ($breadcrumbs) {
    $variables['breadcrumbs'] = $breadcrumbs->toRenderable();
    if (isset($variables['theme'])) {
      // Changing to civic theme to pass other variables to template.
      $variables['breadcrumbs']['#theme'] = 'civic_breadcrumb';
      $variables['breadcrumbs']['#display_theme'] = $variables['theme'];
    }
  }
}

/**
 * Preprocess banner for all nodes.
 */
function _civic_preprocess_civic_banner_node(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = \Drupal::routeMatch()->getParameter('node');

  if (is_null($node)) {
    return;
  }

  // Banner type.
  if ($node->hasField('field_c_n_banner_type') && !$node->get('field_c_n_banner_type')->isEmpty()) {
    $variables['type'] = $node->get('field_c_n_banner_type')->getValue();
  }

  $variables['is_front'] = $is_front = \Drupal::service('path.matcher')->isFrontPage();
  // Banner last updated date.
  if (!$is_front) {
    $variables['content_middle'][] = [
      '#theme' => 'civic_basic_content',
      '#content' => 'Last updated: ' . \Drupal::service('date.formatter')->format($node->getChangedTime(), 'civic_short_date'),
    ];
  }
  else {
    $variables['decorative'] = TRUE;
    $variables['type'] = CIVIC_BANNER_TYPE_LARGE;
  }

  // Banner background.
  if ($node->hasField('field_c_n_banner_background') && !$node->get('field_c_n_banner_background')->isEmpty()) {
    $file_url = $node->get('field_c_n_banner_background')->first()->entity->field_media_image->entity->getFileUri();
    $variables['backgroundImage'] = file_create_url($file_url);
  }

  if ($node->hasField('field_c_n_summary') && !$node->get('field_c_n_summary')->isEmpty()) {
    $variables['content_middle'][] = [
      '#theme' => 'civic_basic_content',
      '#content' => text_summary($node->get('field_c_n_summary')->getString(), NULL, 300),
    ];
  }

  // Banner content components.
  if ($node->hasField('field_c_n_banner_components') && !$node->get('field_c_n_banner_components')->isEmpty()) {
    $entities = $node->field_c_n_banner_components->referencedEntities();
    $render_controller = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
    $variables['content'] = $render_controller->viewMultiple($entities, 'default');
  }
}
