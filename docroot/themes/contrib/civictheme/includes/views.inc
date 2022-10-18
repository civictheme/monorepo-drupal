<?php

/**
 * @file
 * Views component alterations.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\node\Entity\Node;
use Drupal\views\ViewExecutable;

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
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_exposed_form(&$variables) {
  $variables['filter_title'] = t('Filter search results by:');
  $variables['group_filter'] = $variables['form']['#is_large_filter'] ?? FALSE;

  $is_view_component = isset($variables['attributes']['civictheme_automated_list']);
  if ($is_view_component) {
    $variables += $variables['attributes']['civictheme_automated_list'];
    unset($variables['attributes']['civictheme_automated_list']);
    $variables['theme'] = $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT;
    _civictheme_propagate_component_theme_settings($variables['form'], $variables['theme']);
  }

  $variables['theme'] = $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT;
}

/**
 * Propagate component theme setting to nested form elements.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_propagate_component_theme_settings(&$elements, $theme) {
  $form_elements = Element::children($elements);
  foreach ($form_elements as $form_element) {
    $elements[$form_element]['#attributes']['#civictheme_theme'] = $theme;
    $children = Element::children($elements[$form_element]);
    if (!empty($children)) {
      _civictheme_propagate_component_theme_settings($elements[$form_element], $theme);
    }
  }
}

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function civictheme_form_views_exposed_form_alter(&$form, FormStateInterface $form_state) {
  _civictheme_filter_views_exposed_form_alter($form, $form_state);
}

/**
 * Alter exposed views form to render filters.
 *
 * Since Automated list component uses Views view, view exposed form is rendered
 * for Automated list component and standalone views. Filters component
 * properties are applied within view exposed form for both cases.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_filter_views_exposed_form_alter(&$form, FormStateInterface $form_state) {
  $view = $form_state->get('view');

  $filter_form_keys = _civictheme_get_views_exposed_form_elements_keys($form);

  // Retrieve theme from Automated list settings (if this is rendered in the
  // context of an automated list) or use default theme if this rendered as a
  // standalone view.
  $theme = $view->civictheme_automated_list['theme'] ?? CivicthemeConstants::AUTOMATED_LIST_DEFAULT_THEME;

  // Retrieve exposed filter settings.
  $listing_filters = NULL;
  if (!empty($view->civictheme_automated_list_entity)) {
    $listing_show_filters = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_show_filters');
    if ($listing_show_filters) {
      $listing_filters = explode(', ', civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_filters_exp', FALSE, ''));
    }
  }

  // Determine whether to use a large filter based on the number of exposed
  // elements.
  $listing_use_large_filter = count($filter_form_keys) > 1;

  // Convert exposed form elements to the format expected by the filter
  // components.
  $form_element_keys = Element::children($form);
  foreach ($form_element_keys as $form_element_key) {
    // Hide Automated list filter if it is not in a list of selected filters.
    if (
      in_array($form_element_key, $filter_form_keys)
      && is_array($listing_filters)
      && !in_array($form_element_key, $listing_filters)
    ) {
      $form[$form_element_key]['#access'] = FALSE;
      continue;
    }

    $form[$form_element_key]['#attributes']['#civictheme_theme'] = $theme;
    _civictheme_filter_views_exposed_form_alter__process_elements($form[$form_element_key], $form, $listing_use_large_filter);
  }

  $form['#attributes']['data-ct-filter'] = '';
  $form['#attributes']['data-ct-filter-type'] = $listing_use_large_filter ? 'group' : 'single';
  $form['#attributes']['data-ct-filter-ajax-submit-timeout'] = $listing_use_large_filter ? 500 : 0;

  // Made preprocessors know that we are using a large filter.
  // @todo Revisit this as we should not be passing custom values like this.
  $form['#is_large_filter'] = $listing_use_large_filter;

  $form['actions']['submit']['#attributes']['class'] = [
    'ct-button--secondary',
    'ct-button--small',
  ];

  // Ajax exposed view forms hides submit button to have auto-load.
  $ajax_filter = $form_state->get('view')->ajaxEnabled();
  if ($ajax_filter) {
    $form['#attributes']['data-ct-filter-ajax'] = $ajax_filter ? 'true' : 'false';

    $form_state->get('view')->setAjaxEnabled(TRUE);

    $form['actions']['submit']['#attributes']['class'][] = 'hide-xxs';
    $form['actions']['#attributes']['class'][] = 'hide-xxs';

    // Attach node ID to allow access to the paragraph settings.
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => _civictheme_get_node_id_from_form_state($form_state),
    ];
  }

  // Exposed term filter is not updated when terms are added,
  // deleted, or rearranged.
  $tags = $view->display_handler->getCacheMetadata()->getCacheTags();
  $form['#cache']['tags'] = Cache::mergeTags($view->getCacheTags(), $tags);

}

/**
 * Return keys of exposed views form elements.
 *
 * @return array
 *   Array of form keys.
 */
function _civictheme_get_views_exposed_form_elements_keys($form) {
  $keys = [];

  foreach ($form as $key => &$element) {
    if (
      str_starts_with($key, '#')
      || empty($element['#type'])
      || in_array($element['#type'], ['actions', 'hidden'])
    ) {
      continue;
    }
    $keys[] = $key;
  }

  return $keys;
}

/**
 * Alter form elements to work within Large and Basic filters.
 */
function _civictheme_filter_views_exposed_form_alter__process_elements(&$element, $form, $is_large_filter) {
  $valid_process_element_types = ['select', 'radios', 'checkboxes'];
  if (isset($element['#type']) && in_array($element['#type'], $valid_process_element_types)) {
    // Convert select to either radio buttons or checkboxes.
    if (isset($element['#type']) && $element['#type'] === 'select') {
      $element['#type'] = 'radios';

      if (!empty($element['#multiple'])) {
        $element['#type'] = 'checkboxes';
        unset($element['#multiple']);
      }

      _civictheme_filter_views_exposed_form_alter__modify_element_callbacks($element);
    }

    // Only radio buttons and checkboxes work with filter chips.
    $filter_key = !$is_large_filter && in_array($element['#type'] ?? NULL, [
      'radios',
      'checkboxes',
    ]) ? '#ct-single-filter' : '#ct-group-filter';
    $element[$filter_key] = $form['#id'];
    $element['#attributes'][$filter_key] = $form['#id'];
  }
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function civictheme_preprocess_views_view(&$variables) {
  $view = &$variables['view'];

  $is_page_load = isset($view->civictheme_automated_list_entity);

  // Attach view to pager for processing.
  // Mini and full pagers have different theme implementations
  // and so we need to pass the view on different properties to be
  // accessible in the preprocess functions.
  if (!empty($variables['pager'])) {
    if ($view->getPager()->getPluginId() === 'mini') {
      $variables['pager']['#view'] = $view;
    }
    else {
      $variables['pager']['#attributes']['#view'] = $view;
    }
  }

  if ($is_page_load) {
    _civictheme_automated_list_preprocess_view($view->civictheme_automated_list_entity, $view);
  }
  else {
    $listing_paragraph = _civictheme_get_automated_listing_paragraph_from_route();
    _civictheme_automated_list_preprocess_view($listing_paragraph, $view);
  }

  $route_parameters = \Drupal::routeMatch()->getParameters();
  $is_view_page = $route_parameters->get('view_id') !== NULL;
  $is_view_component = isset($variables['view']->civictheme_automated_list);

  // Adds component theme settings to view / Automated list template.
  if ($is_view_component) {
    $variables += $variables['view']->civictheme_automated_list;

    // Override already set title.
    $variables['title'] = $variables['view']->civictheme_automated_list['title'] ?? '';

    // Render 'exposed' as Filters component.
    if (!empty($variables['exposed'])) {
      $variables['filters'] = $variables['exposed'];
      unset($variables['exposed']);
      $variables['filters']['#attributes']['civictheme_automated_list'] = $variables['view']->civictheme_automated_list;
    }

    if (!empty($variables['pager'])) {
      $variables['pager']['#attributes']['#civictheme_theme'] = $variables['view']->civictheme_automated_list['theme'] ?? 'light';
    }
  }

  // Header is displayed within List.
  if ($view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    unset($variables['header']);
    $variables['rows_header'] = $view->header['result']->render();
  }

  // Adding template required classes in for civictheme component.
  $variables['theme'] = $variables['theme'] ?? 'light';
  $variables['with_background'] = $variables['with_background'] ?? $is_view_page;

  $variables['modifier_class'] = [];
  if (!empty($variables['attributes']['class'])) {
    $variables['modifier_class'] = $variables['attributes']['class'];
    unset($variables['attributes']['class']);
  }

  $variables['modifier_class'] = array_merge($variables['modifier_class'], [
    !empty($variables['dom_id']) ? ' js-view-dom-id-' . $variables['dom_id'] : '',
  ]);

  $variables['modifier_class'] = implode(' ', $variables['modifier_class']);

  if (isset($view->civictheme_automated_list['show_filters']) && $view->civictheme_automated_list['show_filters'] === FALSE) {
    $variables['filters'] = NULL;
  }
}

/**
 * Adds preprocessing data to Automated list child components.
 */
function _civictheme_automated_list_preprocess_view($paragraph, ViewExecutable &$view) {
  $settings = [];

  if ($paragraph !== NULL) {
    $link_above = civictheme_get_field_value($paragraph, 'field_c_p_list_link_above', TRUE);
    $link_above_data = $link_above ? [
      'text' => $link_above->get('title')->getString(),
      'is_external' => $link_above->isExternal(),
      'is_new_window' => $link_above->isExternal(),
      'url' => $link_above->getUrl()->toString(),
    ] : [];

    $link_below = civictheme_get_field_value($paragraph, 'field_c_p_list_link_below', TRUE);
    $link_below_data = $link_below ? [
      'text' => $link_below->get('title')->getString(),
      'is_external' => $link_below->isExternal(),
      'is_new_window' => $link_below->isExternal(),
      'url' => $link_below->getUrl()->toString(),
    ] : [];

    $settings = [
      'theme' => civictheme_get_field_theme_value($paragraph),
      'content' => civictheme_get_field_value($paragraph, 'field_c_p_content'),
      'card_theme' => civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme'),
      'rows_header' => isset($view->header['result']) ? $view->header['result']->render() : NULL,
      'link_above' => $link_above_data,
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ?? '',
      'with_background' => civictheme_get_field_value($paragraph, 'field_c_p_background') ?? FALSE,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing') ?? '',
      'show_filters' => civictheme_get_field_value($paragraph, 'field_c_p_list_show_filters') ?? FALSE,
      'link_below' => $link_below_data,
      'cache_tags' => $paragraph->getCacheTags(),
    ];
  }

  // Call modules/theme that implement the hook, and let them change the view.
  \Drupal::moduleHandler()->alter('civictheme_automated_list_preprocess_view', $settings, $view);
  \Drupal::service('theme.manager')->alter('civictheme_automated_list_preprocess_view', $settings, $view);

  $view->civictheme_automated_list = $settings;
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_view_grid(&$variables) {
  $view = $variables['view'] ?? NULL;

  $variables['modifier_class'] = 'ct-card-container--listing';
  // On Grid view title should be added to list.
  if ($view && $view->getStyle()->getBaseId() === 'grid' && isset($view->header['result'])) {
    $variables['title'] = $view->header['result']->render();
  }

  // Adds component theme settings to view / listing template.
  $is_view_component = isset($variables['view']->civictheme_automated_list);
  if ($is_view_component) {
    $variables += $variables['view']->civictheme_automated_list;
  }

  if (isset($variables['card_theme'])) {
    foreach (array_keys($variables['rows']) as $id) {
      $variables['rows'][$id]['#node']->civictheme_theme = $variables['card_theme'];
      $variables['rows'][$id]['#cache']['contexts'][] = 'url.path';
      $variables['rows'][$id]['#cache']['contexts'][] = 'url.query_args';

      if (!empty($variables['view']->civictheme_automated_list['cache_tags'])) {
        $variables['rows'][$id]['#cache']['tags'] = Cache::mergeTags($variables['view']->civictheme_automated_list['cache_tags'], $view->getCacheTags());
      }
    }
  }
}

/**
 * Gets the Automated list paragraph from route.
 *
 * @return \Drupal\paragraphs\Entity\Paragraph|null
 *   First Listing paragraph from the node in route or NULL if no listing
 *   paragraphs are present.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_get_automated_listing_paragraph_from_route() {
  $node = \Drupal::routeMatch()->getParameter('node');

  // Handling an ajax request: try and get from query parameters.
  if (!$node) {
    $nid = \Drupal::request()->get('nid');
    if (is_numeric($nid)) {
      $node = Node::load($nid);
    }
  }

  if ($node && $node->bundle() == 'civictheme_page') {
    $components = civictheme_get_field_referenced_entities($node, 'field_c_n_components');
    foreach ($components as $component) {
      if ($component->getType() == 'civictheme_automated_list') {
        return $component;
      }
    }
  }

  return NULL;
}
