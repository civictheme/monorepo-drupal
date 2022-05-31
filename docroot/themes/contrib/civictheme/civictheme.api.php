<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

/**
 * @addtogroup hooks
 * @{
 */

use Drupal\views\ViewExecutable;

/**
 * Alter the name of view in CivicTheme listing component.
 *
 * @param string $view
 *   The name of the view used in listing component.
 */
function hook_civictheme_listing_view_name_alter(string &$view) {
  $view = 'civictheme_listing';
}

/**
 * Alter the civictheme view preprocess settings.
 *
 * @param array $settings
 *   The preprocess settings of the current view.
 */
function hook_civictheme_listing_preprocess_view_alter(array &$settings, ViewExecutable &$view) {
  if ($view->id() === 'civictheme_view_examples') {
    $settings['theme'] = 'dark';
    $settings['with_background'] = TRUE;
    $settings['vertical_space'] = 'both';
  }
}

/**
 * @} End of "addtogroup hooks".
 */
