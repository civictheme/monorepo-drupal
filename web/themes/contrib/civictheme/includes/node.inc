<?php

/**
 * @file
 * Node related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\node\NodeInterface;

/**
 * Generic pre-process for all view mode of nodes.
 */
function _civictheme_preprocess_node(array &$variables): void {
  $node = $variables['node'];

  if (!$node) {
    return;
  }

  $variables['attributes']['class'][] = 'node';
  if (!$node->isPublished()) {
    $variables['attributes']['class'][] = 'node--unpublished';
  }

  $view_mode = $variables['elements']['#view_mode'];
  // Do not display node title on revision pages.
  if ($view_mode === 'full') {
    unset($variables['label']);
  }

  // Add Table of Contents if the value is set on node.
  if (civictheme_get_field_value($node, 'field_c_n_show_toc')) {
    array_unshift($variables['content'], [
      '#theme' => 'civictheme_table_of_contents',
      '#position' => 'prepend',
      '#anchor_selector' => 'h2',
      '#title' => t('On this page'),
      '#scope_selector' => '.ct-layout__main',
    ]);
  }

  // Add Tags if hide tags is not set on node.
  if (!civictheme_get_field_value($node, 'field_c_n_hide_tags')) {
    $topics = civictheme_get_field_referenced_entities($node, 'field_c_n_topics');
    if (!empty($topics)) {
      $topics = array_map(static function ($topic) {
        return $topic->label();
      }, $topics);

      $variables['content'][] = [
        '#theme' => 'civictheme_tag_list',
        '#tags' => $topics,
        '#vertical_spacing' => 'both',
      ];
    }
  }
}

/**
 * Pre-process Full view mode of nodes.
 */
function _civictheme_preprocess_node__full(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
}

/**
 * Default view mode fallback preprocessor for nodes.
 */
function _civictheme_preprocess_node__default(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
}

/**
 * Pre-process CivicTheme Alert nodes.
 */
function _civictheme_preprocess_node__civictheme_alert__full(array &$variables): void {
  $node = $variables['node'];
  $variables['id'] = $node->id();
  $variables['title'] = $node->label();
  $variables['description'] = _civictheme_process__html_content((string) civictheme_get_field_value($node, 'field_c_n_body', TRUE), [
    // Force dark theme to make sure that text colour has enough contrast with
    // a background.
    'theme' => CivicthemeConstants::THEME_DARK,
  ]);
  $variables['type'] = civictheme_get_field_value($node, 'field_c_n_alert_type');
}

/**
 * Determine last updated date for a page.
 */
function _civictheme_node_get_updated_date(NodeInterface $node, string $format = 'civictheme_short_date'): ?string {
  $show_last_updated = civictheme_get_field_value($node, 'field_c_n_show_last_updated');
  $custom_updated_value = civictheme_get_field_value($node, 'field_c_n_custom_last_updated', TRUE);

  $date = NULL;

  if ($show_last_updated && !empty($custom_updated_value)) {
    $date = civictheme_format_datetime($custom_updated_value->get('value')->getDateTime(), $format);
  }
  elseif ($show_last_updated) {
    $date = civictheme_format_datetime($node->getChangedTime(), $format);
  }

  return $date;
}
