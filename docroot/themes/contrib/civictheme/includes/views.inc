<?php

/**
 * @file
 * Views component alterations.
 */

/**
 * Page pre-process for views.
 */
function _civictheme_preprocess_page__views(&$variables) {
  $route_defaults = \Drupal::routeMatch()->getRouteObject()->getDefaults();
  if (isset($route_defaults['view_id'])) {
    // View pages should not be contained.
    $variables['page']['content_contained'] = FALSE;
  }
}

/**
 * Implements template_preprocess_views_mini_pager().
 */
function civictheme_preprocess_views_mini_pager(&$variables) {
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
function civictheme_preprocess_views_exposed_form(&$variables) {
  $variables['filter_title'] = 'Filter search results by:';
  $variables['large_filter'] = $variables['form']['#large_filter'];
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_view(&$variables) {
  $view = $variables['view'];
  $route_parameters = \Drupal::routeMatch()->getParameters();
  $is_view_page = $route_parameters->get('view_id') !== NULL;
  // Header is displayed within card container.
  if ($view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    unset($variables['header']);
  }
  // Adding template required classes in for civictheme component.
  // @todo allow theming for civictheme listing component via setting / component.
  $variables['theme'] = 'light';
  if (!empty($variables['attributes']['class'])) {
    $variables['modifier_class'] = $variables['attributes']['class'];
    unset($variables['attributes']['class']);
  }
  else {
    $variables['modifier_class'] = [];
  }
  $variables['modifier_class'] = array_merge($variables['modifier_class'], [
    !empty($variables['dom_id']) ? ' js-view-dom-id-' . $variables['dom_id'] : '',
    $is_view_page ? 'civictheme-listing--with-background' : '',
  ]);
  $variables['modifier_class'] = implode(' ', $variables['modifier_class']);
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_view_grid(&$variables) {
  // On Grid view title should be added to card container.
  if ($variables['view']->getStyle()->getBaseId() === 'grid' && isset($variables['view']->header['result'])) {
    $variables['modifier_class'] = 'civictheme-card-container--listing civictheme-card-container--with-background';
    $variables['title'] = $variables['view']->header['result']->render();
  }
}
