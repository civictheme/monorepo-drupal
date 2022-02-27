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
  $view = $variables['view'];
  $route_parameters = \Drupal::routeMatch()->getParameters();
  $is_view_page = $route_parameters->get('view_id') !== NULL;
  // Header is displayed within card container.
  if ($view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    unset($variables['header']);
  }
  // Adding template required classes in for civic component.
  // @todo allow theming for civic listing component via setting / component.
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
    $is_view_page ? 'civic-listing--with-background' : '',
  ]);
  $variables['modifier_class'] = implode(' ', $variables['modifier_class']);

  // Setup items per page filter outside of exposed filter form.
  // Connected to form via JS - see civic.ajax-views.js.
  if (!empty($variables['exposed']) && !empty($variables['exposed']['items_per_page'])) {
    $items_per_page = $view->getItemsPerPage();
    $variables['exposed']['items_per_page']['#default_value'] = $items_per_page;
    $variables['items_per_page'] = $variables['exposed']['items_per_page'];
    $variables['items_per_page']['#id'] = $variables['items_per_page']['#id'] . '--exposed-filter';
    $variables['items_per_page']['#attributes']['data-exposed-filter'] = '';
    $variables['items_per_page']['#attributes']['data-exposed-filter-target'] = $variables['exposed']['items_per_page']['#id'];
    $variables['exposed']['items_per_page'] = [
      '#type' => 'hidden',
      '#id' => $variables['exposed']['items_per_page']['#id'],
      '#value' => $variables['exposed']['items_per_page']['#default_value'],
      '#attributes' => [
        'id' => $variables['exposed']['items_per_page']['#id'],
        'name' => 'items_per_page',
        'data-drupal-selector' => $variables['exposed']['items_per_page']['#attributes']['data-drupal-selector'],
      ],
      '#input' => TRUE,
    ];
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
