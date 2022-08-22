<?php

/**
 * @file
 * Node related functions.
 */

use Drupal\Core\Template\Attribute;

/**
 * Generic pre-process for all view mode of nodes.
 */
function _civictheme_preprocess_node(&$variables) {
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
    $toc_attributes = [];
    $toc_attributes['data-table-of-contents-position'] = 'prepend';
    $toc_attributes['data-table-of-contents-anchor-selector'] = 'h2';
    $toc_attributes['data-table-of-contents-title'] = t('On this page');
    $toc_attributes['data-table-of-contents-anchor-scope-selector'] = '.civictheme-content__main';
    $toc_attributes = new Attribute($toc_attributes);

    array_unshift($variables['content'], [
      '#markup' => '<div class="container"><div class="row"><div class="table-of-contents-container col-m-12" ' . $toc_attributes . '></div></div></div>',
    ]);
  }
}

/**
 * Pre-process Full view mode of nodes.
 */
function _civictheme_preprocess_node__full(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  civictheme_attributes_to_modifier_class($variables);
}

/**
 * Default view mode fallback preprocessor for nodes.
 */
function _civictheme_preprocess_node__default(&$variables) {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];
  $type = $node->getType();
  $type_callback = '_civictheme_preprocess_node__' . $type . '__full';
  if (function_exists($type_callback)) {
    $type_callback($variables);
  }
  civictheme_attributes_to_modifier_class($variables);
}

/**
 * Pre-process CivicTheme alert nodes.
 */
function _civictheme_preprocess_node__civictheme_alert__full(&$variables) {
  $node = $variables['node'];
  $variables['id'] = $node->id();
  $variables['title'] = $node->label();
  $variables['description'] = civictheme_process_html_content(civictheme_get_field_value($node, 'field_c_n_body', TRUE), [
    // Force dark theme to make sure that text colour has enough contrast with
    // a background.
    'theme' => CIVICTHEME_THEME_DARK,
  ]);
  $variables['type'] = civictheme_get_field_value($node, 'field_c_n_alert_type');
}

/**
 * Generic page pre-process for all view mode of nodes.
 */
function _civictheme_preprocess_page__node(&$variables) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = civictheme_load_node_or_revision($variables);

  if (!$node) {
    return;
  }

  // Layout determines whether a node is contained and what it's layout is.
  $variables['page']['content_contained'] = FALSE;

  $variables['vertical_space'] = civictheme_get_field_value($node, 'field_c_n_space');

  // Hide sidebar check.
  $variables['hide_sidebar'] = civictheme_get_field_value($node, 'field_c_n_hide_sidebar') ?? FALSE;
}
