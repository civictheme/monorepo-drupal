<?php

/**
 * @file
 * Node preprocessing.
 */

/**
 * Generic preprocess for all view mode of nodes.
 */
function _civic_preprocess_node(&$variables) {
  $node = $variables['node'];

  // Add Table of Contents if the value is set on node.
  if ($node && $node->hasField('field_c_n_show_toc') && $node->field_c_n_show_toc->getString()) {
    $variables['content_attributes']['data-table-of-contents-position'] = 'prepend';
    $variables['content_attributes']['data-table-of-contents-theme'] = 'light';
    $variables['content_attributes']['data-table-of-contents-anchor-selector'] = 'h2';
    $variables['content_attributes']['data-table-of-contents-title'] = t('On this page');
    // @todo Update selector to '.civic-content--main .civic-content' once
    // container layout is fixed.
    $variables['content_attributes']['data-table-of-contents-anchor-scope-selector'] = '[data-table-of-contents-initialised]';
  }
}

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

/**
 * Preprocess Civic alert nodes.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_node__civic_alert__full(&$variables) {
  $node = $variables['node'];
  $variables['id'] = $node->id();
  $variables['title'] = $node->label();
  $variables['description'] = $variables['content']['body'];
  $variables['type'] = $node->get('field_c_n_alert_type')->getString();
}
