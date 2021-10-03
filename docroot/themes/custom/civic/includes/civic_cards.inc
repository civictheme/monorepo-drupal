<?php

/**
 * @file
 * Card theme alterations.
 */

/**
 * Generic setter of global variables for nodes as card renders.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_card(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  unset($variables['date']);
  $type_callback = '_civic_preprocess_node_' . $type . '_summary';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  $variables['title'] = $node->getTitle();
}

function _civic_preprocess_node_civic_card_event(&$variables) {
  _civic_preprocess_node_card($variables);
  _civic_preprocess_node_date($variables);
  _civic_preprocess_node_thumbnail($variables);
  _civic_preprocess_node_topics($variables);
}

/**
 * Render out a Node as a Navigation Card.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_civic_card_navigation(&$variables) {
  _civic_preprocess_node_card($variables);
  _civic_preprocess_node_thumbnail($variables);
}

/**
 * Render out a Node as a Promo Card.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_civic_card_promo(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $variables['date'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'civic_short_date');
  _civic_preprocess_node_card($variables);
  _civic_preprocess_node_thumbnail($variables);
}

/**
 * Date field preprocessor
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_date(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_date')) {
    if ($date_range = $node->get('field_c_n_date')->getValue()) {
      $variables['date'] = _civic_format_short_date($date_range[0]['value']);
    }
  }
}

function _civic_preprocess_node_topics(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_topics')) {
    if ($topics = $node->get('field_c_n_topics')->getValue()) {
      $tags = [];
      foreach($topics as $topic_data) {
        $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($topic_data['target_id']);
        $tags[] = $term->getName();
      }
      $variables['tags'] = $tags;
    }
  }
}

/**
 * Format dates for cards.
 *
 * @return mixed
 */
function _civic_format_short_date($date) {
  return \Drupal::service('date.formatter')->format(strtotime($date), 'civic_short_date');
}

/**
 * Render thumbnail for Nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_thumbnail(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_thumbnail')) {
    $media_target_id = $node->get('field_c_n_thumbnail')->getString();
    $variables['image'] = _civic_media_image($media_target_id);
  }
}

/**
 * Render Summary from Civic Page nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_civic_page_summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_summary') && !$node->get('field_c_n_summary')->isEmpty()) {
    $variables['summary'] = text_summary($node->get('field_c_n_summary')->getString(), NULL, 100);
  }
}

/**
 * Render Summary from Civic Event nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_civic_event_summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_body') && !$node->get('field_c_n_body')->isEmpty()) {
    $value = $node->get('field_c_n_body')->getValue();
    $variables['summary'] = $value[0]['value'];
  }
}
