<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

use Drupal\views\ViewExecutable;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Alter the name of view in CivicTheme listing component.
 *
 * @param string $view_name
 *   The name of the view used in listing component.
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The paragraph containing view conditions.
 */
function hook_civictheme_listing_view_name_alter(string &$view_name, string &$display_id, Paragraph $paragraph) {
  $view_name = 'civictheme_listing';
  $display_id = 'block1';

  if ($paragraph->hasField('field_c_p_listing_type') && !$paragraph->get('field_c_p_listing_type')->isEmpty()) {
    [$view_name, $display_id] = explode('__', $paragraph->get('field_c_p_listing_type')->getString());
  }
}

/**
 * Alter the civictheme view preprocess settings.
 *
 * @param array $settings
 *   The preprocess settings of the current view.
 */
function hook_civictheme_listing_preprocess_view_alter(array &$settings, ViewExecutable &$view) {
  if ($view->id() === 'civictheme_view_examples') {
    $settings['theme'] = CIVICTHEME_THEME_DARK;
    $settings['with_background'] = TRUE;
    $settings['vertical_space'] = 'both';
  }
}
