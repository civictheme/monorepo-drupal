<?php

/**
 * @file
 * Alert related functions.
 */

/**
 * Page pre-process to support showing of alerts.
 */
function _civictheme_preprocess_page__civictheme_alert(&$variables) {
  $variables['attributes']['data-component-name'] = 'civictheme-alerts';
  $variables['attributes']['data-alert-endpoint'] = '/api/civictheme-alerts?_format=json';
}
