<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\ViewExecutable;

/**
 * Alter the view info used in the Automated list component.
 *
 * @param array $info
 *   View info array to alter passed by reference. Keys are:
 *   - view_name: (string) A view machine name.
 *   - display_name: (string) A view display machine name.
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The Automated list component paragraph.
 */
function hook_civictheme_automated_list_view_info_alter(array &$info, Paragraph $paragraph) {
  // Change the view name and block based on the conditions set in the
  // Automated list paragraph.
  if (civictheme_get_field_value($paragraph, 'field_c_p_content_type') == 'event') {
    // Use a custom display id.
    $info['view_name'] = 'my_custom_view';
    $info['display_name'] = 'my_custom_block_1';
  }
  elseif (civictheme_get_field_value($paragraph, 'field_c_p_content_type') == 'profile') {
    // Use a 'default' display id.
    $info['my_other_view'] = 'my_custom_view';
  }
}

/**
 * Alter the civictheme view preprocess settings.
 *
 * @param array $settings
 *   The preprocess settings of the current view.
 */
function hook_civictheme_automated_list_preprocess_view_alter(array &$settings, ViewExecutable &$view) {
  if ($view->id() === 'civictheme_view_examples') {
    $settings['theme'] = CIVICTHEME_THEME_DARK;
    $settings['with_background'] = TRUE;
    $settings['vertical_space'] = 'both';
  }
}
