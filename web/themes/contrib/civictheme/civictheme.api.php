<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\views\ViewExecutable;

/**
 * Alter the view info used in the Automated list component.
 *
 * @param array $info
 *   View info array to alter passed by reference. Keys are:
 *   - view_name: (string) A view machine name.
 *   - display_name: (string) A view display machine name.
 * @param array $settings
 *   The Automated list component settings passed by reference with the
 *   following keys:
 *   - title: (string) List title.
 *   - type: (string) List type (view name that powers Automated list).
 *   - content_type: (string) Content type to filter by.
 *   - limit: (int) Results limit.
 *   - limit_type: (string) Results limit type: 'limited' or 'unlimited'.
 *   - filters_exp: (array) Array of exposed filter names.
 *   - view_as: (string) The name of the view mode of the result item.
 *   - theme: (string) The view theme: light or dark.
 *   - item_theme: (string) The name of the theme for an item: light or dark.
 *   - topics: (array) Array of Topic entities.
 *   - site_sections: (array) Array of Site section entities.
 *   - cache_tags: (array) Array of the cache tags.
 */
function hook_civictheme_automated_list_view_info_alter(array &$info, array &$settings): void {
  // Change the view name and block based on the conditions set in the
  // Automated list settings.
  if ($settings['content_type'] == 'event') {
    // Use a custom display name for 'Event' content type.
    $info['display_name'] = 'my_custom_block_1';
  }
  elseif ($settings['content_type'] == 'profile') {
    // Use a custom view for a custom content type 'profile' (which is not a
    // part of CivicTheme) with a 'default' display name (implicitly).
    $info['view_name'] = 'my_custom_view';
  }
}

/**
 * Alter the CivicTheme view preprocess settings.
 *
 * @param array $variables
 *   Array of preprocess variables of the Automated list view.
 */
function hook_civictheme_automated_list_preprocess_view_alter(array &$variables, ViewExecutable &$view): void {
  if ($view->id() == 'civictheme_view_examples') {
    $variables['theme'] = CivicthemeConstants::THEME_DARK;
    $variables['with_background'] = TRUE;
    $variables['vertical_spacing'] = 'both';
  }
}
