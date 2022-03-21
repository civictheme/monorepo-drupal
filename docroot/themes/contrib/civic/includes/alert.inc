<?php

/**
 * @file
 * Alert related functions.
 */

/**
 * Page pre-process to support showing of alerts.
 */
function _civic_preprocess_page__civic_alert(&$variables) {
  $variables['attributes']['data-component-name'] = 'civic-alerts';
  $variables['attributes']['data-alert-endpoint'] = '/api/civic-alerts?_format=json';
}
