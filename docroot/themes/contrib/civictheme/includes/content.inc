<?php

/**
 * @file
 * CivicTheme Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_content(&$variables) {
  $content = '';
  if (!empty($variables['content']['field_c_p_content'])) {
    $content = \Drupal::service('renderer')->render($variables['content']['field_c_p_content']);
  }
  // Replace theme variable with component level component theme.
  // Note: All style dropdowns and components need to use civictheme's default
  // theme light in order to be updated with the correct component set theme.
  // Example: Button style dropdown classes are:
  // .civictheme-button.civictheme-theme-light.civictheme-button--primary.civictheme-button--regular
  // if the component is dark we str_replace the theme class and replace.
  $content = str_replace('civictheme-theme-light', 'civictheme-theme-' . $variables['theme'], $content);
  // Post processing for all WYSIWYG fields.
  $content = civictheme_process_link_attributes($content);
  $variables['content'] = $content;
}
