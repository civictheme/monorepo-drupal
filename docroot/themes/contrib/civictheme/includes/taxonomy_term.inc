<?php

/**
 * @file
 * Taxionomy term theme alterations..
 */

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civictheme_theme_suggestions_taxonomy_term_alter(array &$suggestions, &$variables) {
  if (!empty($variables['elements']['#view_mode'])) {
    $sanitized_view_mode = strtr($variables['elements']['#view_mode'], '.', '_');
    $suggestions[] = 'taxonomy_term__civictheme_' . $sanitized_view_mode;
  }
}

/**
 * Pre-process Chip view mode of term.
 */
function _civictheme_preprocess_taxonomy_term__chip(&$variables) {
  $term = $variables['term'];

  if (!$term) {
    return;
  }

  $variables['content'] = $term->label();
}
