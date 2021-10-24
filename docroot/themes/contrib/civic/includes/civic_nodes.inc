<?php

/**
 * @file
 * Node preprocessing.
 */

/**
 * Preprocess Full view mode of nodes.
 *
 * @see civic_preprocess_node()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__full(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civic_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  _civic_attributes($variables);
}

/**
 * Default view mode fallback preprocessor for nodes.
 *
 * @see civic_preprocess_node()
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__default(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civic_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  _civic_attributes($variables);
}

/**
 * Preprocess Civic Page nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_page__full(&$variables) {
  $variables['components'] = $variables['content']['field_c_n_components'];
}
