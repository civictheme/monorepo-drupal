<?php

/**
 * @file
 * Card theme alterations.
 */

use \Drupal\file\Entity\File;

/**
 * Render out a Node as a Navigation Card.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_civic_card_navigation(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civic_preprocess_node_' . $type . '_summary';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  $variables['title'] = $node->getTitle();
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
  $type = $node->getType();
  $type_callback = '_civic_preprocess_node_' . $type . '_summary';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  $variables['title'] = $node->getTitle();
  $variables['date'] = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'civic_short_date');
  _civic_preprocess_node_thumbnail($variables);
}

/**
 * Render thumbnail for Nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node_thumbnail(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  if (isset($variables['content']['field_c_n_thumbnail'][0]['#media'])) {
    $media_content = $variables['content']['field_c_n_thumbnail'][0]['#media'];
    $media_image_data = $media_content->field_media_image[0];
    $media_target_id = $media_image_data->target_id ?? NULL;
    $alt_tag = $media_image_data->alt;
  }
  elseif (!$node->get('field_c_n_thumbnail')->isEmpty()) {
    $alt_tag = '';
    $media_target_id = $node->get('field_c_n_thumbnail')->getString();
  }
  if (isset($media_target_id)) {
    if ($file = File::load($media_target_id)) {
      $image['src'] = file_create_url($file->getFileUri());
      $image['alt'] = $alt_tag;
      $variables['image'] = $image;
    }
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
    $variables['summary'] = $node->get('field_c_n_summary')->getString();
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
