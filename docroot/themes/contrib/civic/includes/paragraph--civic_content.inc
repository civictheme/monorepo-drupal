<?php

/**
 * @file
 * Civic Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_content(&$variables) {
  _civic_preprocess_paragraph($variables);
  $content = '';
  if (!empty($variables['content']['field_c_p_content'])) {
    $content = \Drupal::service('renderer')->render($variables['content']['field_c_p_content']);
  }

  $variables['content'] = $content;
}
