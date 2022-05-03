<?php

/**
 * @file
 * Hooks related to the CivicTheme theme.
 */

/**
 * @addtogroup hooks
 * @{
 */

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
 * @} End of "addtogroup hooks".
 */
