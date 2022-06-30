<?php

/**
 * @file
 * Card theme alterations.
 */

/**
 * Generic setter of global variables for nodes as card renders.
 */
function _civictheme_preprocess_node__card(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  unset($variables['date']);
  $type_callback = '_civictheme_preprocess_node__' . $type . '__summary';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  $variables['title'] = $node->getTitle();
  civictheme_attributes_to_modifier_class($variables);
  _civictheme_preprocess_node__theme($variables);
}

/**
 * Preprocess.
 */
function _civictheme_preprocess_node__civictheme_event_card(&$variables) {
  _civictheme_preprocess_node__card($variables);
  _civictheme_preprocess_node__date($variables);
  _civictheme_preprocess_node__thumbnail($variables);
  _civictheme_preprocess_node__topics($variables);
}

/**
 * Pre-process reference Theme fields for CivicTheme Nodes.
 */
function _civictheme_preprocess_node__theme(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if (isset($node->_referringItem)) {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $referring_paragraph = $node->_referringItem->getEntity();
    if ($theme = _civictheme_paragraph_get_theme($referring_paragraph)) {
      $variables['theme'] = $theme;
    }
  }
  // For when theme is set by view or other third party.
  if (isset($node->civictheme_theme)) {
    $variables['theme'] = $node->civictheme_theme;
  }
}

/**
 * Pre-process a Node for navigation_card view mode.
 */
function _civictheme_preprocess_node__civictheme_navigation_card(&$variables) {
  _civictheme_preprocess_node__card($variables);
  _civictheme_preprocess_node__thumbnail($variables);
}

/**
 * Pre-process a Node for civictheme_subject view mode.
 */
function _civictheme_preprocess_node__civictheme_promo_card(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $variables['date'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'civictheme_short_date');
  _civictheme_preprocess_node__card($variables);
  _civictheme_preprocess_node__thumbnail($variables);
  _civictheme_preprocess_node__topics($variables);
}

/**
 * Pre-process a Node for civictheme_subject_card view mode.
 */
function _civictheme_preprocess_node__civictheme_subject_card(&$variables) {
  _civictheme_preprocess_node__card($variables);
  _civictheme_preprocess_node__thumbnail($variables);
}

/**
 * Pre-process date field.
 */
function _civictheme_preprocess_node__date(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_date')) {
    if ($date_range = $node->get('field_c_n_date')->getValue()) {
      $variables['date'] = civictheme_format_short_date($date_range[0]['value']);
    }
  }
}

/**
 * Topic field preprocessor.
 */
function _civictheme_preprocess_node__topics(&$variables) {
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
function _civictheme_preprocess_node__thumbnail(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_thumbnail') && !$node->get('field_c_n_thumbnail')->isEmpty()) {
    $media = $node->get('field_c_n_thumbnail')->first()->get('entity')->getTarget()->getValue();
    $variables['image'] = civictheme_media_image_process_variables($media);
  }
}

/**
 * Pre-process Summary for civictheme_page nodes.
 */
function _civictheme_preprocess_node__civictheme_page__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_summary') && !$node->get('field_c_n_summary')->isEmpty()) {
    $variables['summary'] = text_summary($node->get('field_c_n_summary')->getString(), NULL, 100);
  }
}

/**
 * Pre-process Summary for civictheme_event.
 */
function _civictheme_preprocess_node__civictheme_event__summary(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if ($node->hasField('field_c_n_body') && !$node->get('field_c_n_body')->isEmpty()) {
    $value = $node->get('field_c_n_body')->getValue();
    $variables['summary'] = $value[0]['value'];
  }
  _civictheme_preprocess_node__date($variables);
}
