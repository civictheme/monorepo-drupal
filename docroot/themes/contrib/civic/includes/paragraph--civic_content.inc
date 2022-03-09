<?php

/**
 * @file
 * Civic Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civic_preprocess_paragraph__civic_content(&$variables) {
  _civic_preprocess_paragraph($variables);
  $content = '';
  if (!empty($variables['content']['field_c_p_content'])) {
    $content = \Drupal::service('renderer')->render($variables['content']['field_c_p_content']);
  }
  // Replace theme variable with component level component theme.
  // Note: All style dropdowns and components need to use civic's default theme
  // theme light in order to be updated with the correct component set theme.
  // Example: Button style dropdown classes are:
  // .civic-button.civic-theme-light.civic-button--primary.civic-button--regular
  // if the component is dark we str_replace the theme class and replace.
  $content = str_replace('civic-theme-light', 'civic-theme-' . $variables['theme'], $content);
  $variables['content'] = $content;
}
