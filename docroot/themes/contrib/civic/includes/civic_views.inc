<?php

/**
 * @file
 * Views component alterations.
 */

/**
 * Implements template_preprocess_views_mini_pager().
 */
function civic_preprocess_views_mini_pager(&$variables) {
  if (isset($variables['items']['current'])) {
    $variables['items']['pages'] = [
      $variables['items']['current'] => [
        'href' => '#',
      ],
    ];
  }
}
