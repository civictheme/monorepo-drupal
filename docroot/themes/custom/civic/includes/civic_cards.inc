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
function _civic_preprocess_node__card(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  unset($variables['date']);
  $type_callback = '_civic_preprocess_node__' . $type . '__summary';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  $variables['title'] = $node->getTitle();
  _civic_attributes($variables);
  _civic_preprocess_node__theme($variables);
}

/**
 * Preprocess.
 *
 * @see civic_preprocess_node()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_card_event(&$variables) {
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__date($variables);
  _civic_preprocess_node__thumbnail($variables);
  _civic_preprocess_node__topics($variables);
}

/**
 * Preprocess reference Theme fields for Civic Nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__theme(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if (isset($node->_referringItem)) {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $referring_paragraph = $node->_referringItem->getEntity();
    if ($theme = _civic_paragraph_get_theme($referring_paragraph)) {
      $variables['theme'] = $theme;
    }
  }
}

/**
 * Preprocess a Node for navigation_card view mode.
 *
 * @see civic_preprocess_node()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_card_navigation(&$variables) {
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__thumbnail($variables);
}

/**
 * Preprocess a Node for civic_promo_card view mode.
 *
 * @see civic_preprocess_node()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_card_promo(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $variables['date'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'civic_short_date');
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__thumbnail($variables);
}

/**
 * Preprocess date field.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__date(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_date')) {
    if ($date_range = $node->get('field_c_n_date')->getValue()) {
      $variables['date'] = _civic_format_short_date($date_range[0]['value']);
    }
  }
}

/**
 * Topic field preprocessor.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__topics(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_topics')) {
    $tags = [];
    foreach ($node->get('field_c_n_topics')->referencedEntities() as $term) {
      $tags[] = $term->getName();
    }
    $variables['tags'] = $tags;
  }
}

/**
 * Format dates for cards.
 *
 * @return mixed
 *   Formatted date.
 */
function _civic_format_short_date($date) {
  return \Drupal::service('date.formatter')->format(strtotime($date), 'civic_short_date');
}

/**
 * Preprocess image thumbnail for nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__thumbnail(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_thumbnail') && !$node->get('field_c_n_thumbnail')->isEmpty()) {
    $media = $node->get('field_c_n_thumbnail')->first()->get('entity')->getTarget()->getValue();
    $variables['image'] = _civic_media_image($media);
  }
}

/**
 * Preprocess Summary for civic_page nodes.
 *
 * @see _civic_preprocess_node__card()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_page__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_summary') && !$node->get('field_c_n_summary')->isEmpty()) {
    $variables['summary'] = text_summary($node->get('field_c_n_summary')->getString(), NULL, 100);
  }
}

/**
 * Preprocess Summary for civic_event.
 *
 * @see _civic_preprocess_node__card()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_event__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_body') && !$node->get('field_c_n_body')->isEmpty()) {
    $value = $node->get('field_c_n_body')->getValue();
    $variables['summary'] = $value[0]['value'];
  }
}
