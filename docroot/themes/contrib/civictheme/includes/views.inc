<?php

/**
 * @file
 * Views component alterations.
 */

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

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
  $view = $variables['view'] ?? NULL;
  // On Grid view title should be added to card container.
  if ($view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    $variables['modifier_class'] = 'civictheme-card-container--listing civictheme-card-container--with-background';
    $variables['title'] = $view->header['result']->render();
  }
  _civictheme_preprocess_views_view_grid__listing_component($variables);
}

/**
 * Add `civictheme_listing` paragraph options to view.
 */
function _civictheme_preprocess_views_view_grid__listing_component(&$variables) {
  $listing_paragraph = _civictheme_get_listing_paragraph_from_route();
  if ($listing_paragraph !== NULL) {
    $variables['theme'] = $listing_paragraph->hasField('field_c_p_theme') && $listing_paragraph->get('field_c_p_theme')->isEmpty() ? 'light' : $listing_paragraph->get('field_c_p_theme')->getString();
    $variables['with_background'] = $listing_paragraph->hasField('field_c_p_background') && $listing_paragraph->get('field_c_p_background')->isEmpty() ? '' : $listing_paragraph->get('field_c_p_background')->getString();
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

/**
 * Check if paragraph has a non-empty field.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The paragraph to check.
 * @param string $field_name
 *   Field name to check.
 *
 * @return bool
 *   TRUE if the paragraph is not empty and has a non-empty specified field.
 */
function _civictheme_paragraph_field_has_value(Paragraph $paragraph, $field_name) {
  return $paragraph && $paragraph->hasField($field_name) && !$paragraph->get($field_name)->isEmpty();
}
