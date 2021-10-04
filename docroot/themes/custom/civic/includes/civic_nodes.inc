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
  $type_callback = '_civic_preprocess_node__full__' . $type;
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
function _civic_preprocess_node__full__civic_page(&$variables) {
  $variables['components'] = $variables['content']['field_c_n_components'];
}
