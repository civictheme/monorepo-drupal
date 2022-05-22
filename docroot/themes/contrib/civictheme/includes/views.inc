<?php

/**
 * @file
 * Views component alterations.
 */

use Drupal\node\Entity\Node;
use Drupal\Core\Render\Element;

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
  $is_view_component = isset($variables['attributes']['civictheme_listing']);
  if ($is_view_component) {
    $variables += $variables['attributes']['civictheme_listing'];
    unset($variables['attributes']['civictheme_listing']);
    _civictheme_propagate_component_theme_settings($variables['form'], $variables['theme']);
  }
  $variables['theme'] = $variables['theme'] ?? 'light';
}

/**
 * Propagate component theme setting to nested form elements.
 */
function _civictheme_propagate_component_theme_settings(&$elements, $theme) {
  $form_elements = Element::children($elements);
  foreach ($form_elements as $form_element) {
    $elements[$form_element]['#attributes']['#civic_theme'] = $theme;
    $children = Element::children($elements[$form_element]);
    if (!empty($children)) {
      _civictheme_propagate_component_theme_settings($elements[$form_element], $theme);
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_view(&$variables) {
  $view = $variables['view'];
  $route_parameters = \Drupal::routeMatch()->getParameters();
  $is_view_page = $route_parameters->get('view_id') !== NULL;
  $is_view_component = isset($variables['view']->civictheme_listing);

  // Adds component theme settings to view / listing template.
  if ($is_view_component) {
    $variables += $variables['view']->civictheme_listing;
    // Override already set title.
    $variables['title'] = $variables['view']->civictheme_listing['title'];
    // Maybe remove?
    if (!empty($variables['exposed'])) {
      $variables['exposed']['#attributes']['civictheme_listing'] = $variables['view']->civictheme_listing;
    }
    if (!empty($variables['pager'])) {
      $variables['pager']['#attributes']['#civic_theme'] = $variables['view']->civictheme_listing['theme'];
    }
  }
  // Header is displayed within card container.
  if ($view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    unset($variables['header']);
  }
  // Adding template required classes in for civictheme component.
  $variables['theme'] = $variables['theme'] ?? 'light';
  $variables['with_background'] = $variables['with_background'] ?? $is_view_page;

  if (!empty($variables['attributes']['class'])) {
    $variables['modifier_class'] = $variables['attributes']['class'];
    unset($variables['attributes']['class']);
  }
  else {
    $variables['modifier_class'] = [];
  }
  $variables['modifier_class'] = array_merge($variables['modifier_class'], [
    !empty($variables['dom_id']) ? ' js-view-dom-id-' . $variables['dom_id'] : '',
  ]);
  $variables['modifier_class'] = implode(' ', $variables['modifier_class']);
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_view_grid(&$variables) {
  $view = $variables['view'] ?? NULL;
  // On Grid view title should be added to card container.
  if ($view && $view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    $variables['modifier_class'] = 'civictheme-card-container--listing civictheme-card-container--with-background';
    $variables['title'] = $view->header['result']->render();
  }
  // Adds component theme settings to view / listing template.
  $is_view_component = isset($variables['view']->civictheme_listing);
  if ($is_view_component) {
    $variables += $variables['view']->civictheme_listing;
  }
}

/**
 * Gets the Listing paragraph from route.
 *
 * @return \Drupal\paragraphs\Entity\Paragraph|null
 *   First Listing paragraph from the node in route or NULL if no listing
 *   paragraphs are present.
 */
function _civictheme_get_listing_paragraph_from_route() {
  $node = \Drupal::routeMatch()->getParameter('node');

  // Handling an ajax request: try and get from query parameters.
  if (!$node) {
    $nid = \Drupal::request()->get('nid');
    if (is_numeric($nid)) {
      $node = Node::load($nid);
    }
  }

  if ($node && $node->bundle() == 'civictheme_page') {
    if (!$node->get('field_c_n_components')->isEmpty()) {
      foreach ($node->get('field_c_n_components')->referencedEntities() as $paragraph) {
        if ($paragraph->getType() == 'civictheme_listing') {
          return $paragraph;
        }
      }
    }
  }

  return NULL;
}
