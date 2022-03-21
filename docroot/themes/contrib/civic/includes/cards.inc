<?php

/**
 * @file
 * Card theme alterations.
 */

/**
 * Generic setter of global variables for nodes as card renders.
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
  civic_attributes_to_modifier_class($variables);
  _civic_preprocess_node__theme($variables);
}

/**
 * Preprocess.
 */
function _civic_preprocess_node__civic_card_event(&$variables) {
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__date($variables);
  _civic_preprocess_node__thumbnail($variables);
  _civic_preprocess_node__topics($variables);
}

/**
 * Pre-process reference Theme fields for Civic Nodes.
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
 * Pre-process a Node for navigation_card view mode.
 */
function _civic_preprocess_node__civic_card_navigation(&$variables) {
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__thumbnail($variables);
}

/**
 * Pre-process a Node for civic_subject view mode.
 */
function _civic_preprocess_node__civic_card_promo(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $variables['date'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'civic_short_date');
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__thumbnail($variables);
  _civic_preprocess_node__topics($variables);
}

/**
 * Pre-process a Node for civic_card_subject view mode.
 */
function _civic_preprocess_node__civic_card_subject(&$variables) {
  _civic_preprocess_node__card($variables);
  _civic_preprocess_node__thumbnail($variables);
}

/**
 * Pre-process date field.
 */
function _civic_preprocess_node__date(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_date')) {
    if ($date_range = $node->get('field_c_n_date')->getValue()) {
      $variables['date'] = civic_format_short_date($date_range[0]['value']);
    }
  }
}

/**
 * Topic field preprocessor.
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
 * Pre-process image thumbnail for nodes.
 */
function _civic_preprocess_node__thumbnail(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_thumbnail') && !$node->get('field_c_n_thumbnail')->isEmpty()) {
    $media = $node->get('field_c_n_thumbnail')->first()->get('entity')->getTarget()->getValue();
    $variables['image'] = civic_media_image_variables($media);
  }
}

/**
 * Pre-process Summary for civic_page nodes.
 */
function _civic_preprocess_node__civic_page__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_summary') && !$node->get('field_c_n_summary')->isEmpty()) {
    $variables['summary'] = text_summary($node->get('field_c_n_summary')->getString(), NULL, 100);
  }
}

/**
 * Pre-process Summary for civic_event.
 */
function _civic_preprocess_node__civic_event__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_body') && !$node->get('field_c_n_body')->isEmpty()) {
    $value = $node->get('field_c_n_body')->getValue();
    $variables['summary'] = $value[0]['value'];
  }
}
