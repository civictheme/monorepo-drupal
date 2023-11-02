<?php

/**
 * @file
 * Snippet theme alterations.
 */

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_snippet(&$variables) {
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__summary($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_snippet_ref(&$variables) {
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_node().
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_node__civictheme_snippet(&$variables) {
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  // @see civictheme_preprocess_views_view_grid()
  $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
}
