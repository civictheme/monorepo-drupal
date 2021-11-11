<?php

/**
 * @file
 * Hooks related to the Civic theme.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alter the name of view in Civic listing component.
 *
 * @param string $view
 *   The name of the view used in listing component.
 */
function hook_civic_listing_view_name_alter(string &$view) {
  $view = 'civic_listing';
}

/**
 * @} End of "addtogroup hooks".
 */
