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

/**
 * Implements hook_preprocess_HOOK().
 */
function civic_preprocess_views_exposed_form(&$variables) {
  $variables['filter_title'] = 'Filter search results by:';
  $variables['large_filter'] = $variables['form']['#large_filter'];
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civic_preprocess_views_view(&$variables) {
  // Header is displayed within card container.
  if ($variables['view']->getStyle()->getBaseId() === 'grid' && isset($variables['view']->header['result'])) {
    unset($variables['header']);
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civic_preprocess_views_view_grid(&$variables) {
  // On Grid view title should be added to card container.
  if ($variables['view']->getStyle()->getBaseId() === 'grid' && isset($variables['view']->header['result'])) {
    $variables['modifier_class'] = 'civic-card-container--listing civic-card-container--with-background';
    $variables['title'] = $variables['view']->header['result']->render();
  }
}
