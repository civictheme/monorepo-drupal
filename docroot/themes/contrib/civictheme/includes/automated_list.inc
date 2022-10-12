<?php

/**
 * @file
 * Automated list paragraph component.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\Views;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_automated_list(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Get the automated list element.
  $variables['automated_list'] = _civictheme_automated_list_element($paragraph);

  $attributes = [];
  $attributes['id'] = Html::getUniqueId('listing-' . $paragraph->id());
  if (!empty($variables['automated_list']['view_id'])) {
    $attributes['data-view'] = Html::cleanCssIdentifier($variables['automated_list']['#view_name']);
    $attributes['data-view-display'] = Html::cleanCssIdentifier($variables['automated_list']['#display_name']);
  }

  $variables['attributes'] = $variables['attributes'] ?? [];
  $variables['modifier_class'] = !empty($variables['attributes']['class']) ? implode(' ', $variables['attributes']['class']) : NULL;
  unset($variables['attributes']['class']);
  $variables['attributes'] = $variables['attributes'] + $attributes;

  // @todo Review why we need these variables.
  $variables['view_as'] = civictheme_get_field_value($paragraph, 'field_c_p_list_item_view_as');
  $variables['limit'] = civictheme_get_field_value($paragraph, 'field_c_p_list_limit');

  // Propagate Automated list theme settings to the view filters.
  // @todo Possibly move these into _civictheme_automated_list_element().
  if (!empty($variables['automated_list']['view']['#view'])) {
    $form_elements = Element::children($variables['automated_list']['view']['#view']->exposed_widgets);
    foreach ($form_elements as $form_element) {
      $variables['automated_list']['view']['#view']->exposed_widgets[$form_element]['#civictheme_theme'] = $variables['theme'];
    }
  }
}

/**
 * Render Automated list paragraph.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   The paragraph containing Automated list component configurations.
 *
 * @return array
 *   The render array for view and exposed form.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_automated_list_element(Paragraph $paragraph) {
  $element = NULL;

  $info = _civictheme_automated_list_get_view_info($paragraph);

  $view = Views::getView($info['view_name']);
  if (!$view) {
    \Drupal::logger('civictheme')->error(sprintf('Unable to set Automated list view "%s".', $info['view_name']));

    // Do not render this element if the display does not exist.
    return NULL;
  }

  if (!$view->setDisplay($info['display_name'])) {
    \Drupal::logger('civictheme')->error(sprintf('Unable to set Automated list view display "%s" for view "%s".', $info['display_name'], $info['view_name']));

    // Do not render this element if the display does not exist.
    return NULL;
  }

  // Add config entity to provide access to fields in process hooks.
  $view->civictheme_automated_list_entity = $paragraph;

  // Adjust view based on configuration provided in
  // 'civictheme_automated_list_entity' field.
  _civictheme_automated_list_view_alter($view);

  $element['#view_name'] = $info['view_name'];
  $element['#display_name'] = $info['display_name'];

  // Render view.
  $element['view'] = $view->executeDisplay($info['display_name']);

  return $element;
}

/**
 * Get the Automated list view name and display name.
 *
 * @param object|null $paragraph
 *   Configuration paragraph object.
 *
 * @return array|null
 *   Array of view information with 'view_name' and 'display_name' filled in or
 *   NULL if info was not correctly provided (during alteration, for example).
 *
 * @see hook_civictheme_automated_list_view_info_alter()
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_automated_list_get_view_info($paragraph) {
  $info = [
    'view_name' => CIVICTHEME_AUTOMATED_LIST_DEFAULT_VIEW_NAME,
    'display_name' => CIVICTHEME_AUTOMATED_LIST_DEFAULT_VIEW_DISPLAY_NAME,
  ];

  if (!empty($paragraph)) {
    $info_from_field = civictheme_get_field_value($paragraph, 'field_c_p_list_type');
    $info_from_field = _civictheme_automated_list_parse_view_info($info_from_field);
    $info = !empty($info_from_field) ? $info_from_field : $info;

    \Drupal::moduleHandler()->alter('civictheme_automated_list_view_info', $info, $paragraph);
    \Drupal::service('theme.manager')->alter('civictheme_automated_list_view_info', $info, $paragraph);
  }

  return $info;
}

/**
 * Loads and alters Automated list view.
 *
 * Please, do not optimise conditions in this function - the code is organised
 * this way on purpose to ease readability and debugging.
 *
 * @param object $view
 *   View object to later. Note that the view object already has
 *   'civictheme_automated_list_entity' field populated.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.MissingImport)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_automated_list_view_alter($view) {
  if (!property_exists($view, 'civictheme_automated_list_entity')) {
    throw new \Exception('Listing view object is missing required property "civictheme_automated_list_entity".');
  }

  $listing_title = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_title');
  $listing_content_type = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_content_type');
  $listing_topics = civictheme_get_entity_field_key_values($view->civictheme_automated_list_entity, 'field_c_p_list_topics', 'target_id');
  $listing_site_sections = civictheme_get_entity_field_key_values($view->civictheme_automated_list_entity, 'field_c_p_list_site_sections', 'target_id');
  $listing_item_view_as = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_item_view_as');
  $listing_limit_type = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_limit_type');
  $listing_limit = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_limit');
  $listing_items_per_page = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_limit');
  $listing_show_filters = civictheme_get_field_value($view->civictheme_automated_list_entity, 'field_c_p_list_show_filters');

  $view_args = [];
  // First view argument - content types.
  $view_args[] = !empty($listing_content_type) ? $listing_content_type : 'all';
  // Second view argument - Topics.
  $view_args[] = !empty($listing_topics) ? implode('+', $listing_topics) : 'all';
  // Third view argument - Site Sections.
  $view_args[] = !empty($listing_site_sections) ? implode('+', $listing_site_sections) : 'all';
  $view->setArguments($view_args);

  // Title.
  $view->setTitle($listing_title);

  // Items view mode.
  if (!empty($listing_item_view_as)) {
    $view_mode_options = $view->display_handler->getOption('row');
    $view_mode_options['options']['view_mode'] = $listing_item_view_as;
    $view->display_handler->setOption('row', $view_mode_options);
  }

  // Pager.
  $view_header = $view->display_handler->getOption('header');

  // Show exact number of items.
  if ($listing_limit_type == CIVICTHEME_AUTOMATED_LIST_LIMIT_TYPE_LIMITED) {
    // Prevent endless lists by using a fallback limit if a limit is 0.
    $listing_limit = $listing_limit > 0 ? $listing_limit : CIVICTHEME_AUTOMATED_LIST_LIMIT_FALLBACK;

    // Changing pager type to 'some' and setting 'items_per_page'.
    $view_pager = $view->display_handler->getOption('pager');
    $view_pager['type'] = 'some';
    $view_pager['options']['items_per_page'] = $listing_limit;
    $view->display_handler->setOption('pager', $view_pager);

    $view_header = [];
    if ($listing_show_filters && isset($view_header['result']['content'])) {
      $view_header['result']['content'] = 'Showing @end of @total';
    }
    $view->display_handler->setOption('header', $view_header);
  }
  elseif ($listing_limit_type == CIVICTHEME_AUTOMATED_LIST_LIMIT_TYPE_UNLIMITED) {
    if ($listing_items_per_page == 0) {
      // Show pager and default number of items.
      // This is default view display state, so no change is required.
      // Do not optimise - left empty for clarity.
    }
    else {
      // Show pager with specified number of items per page.
      // Re-using existing pager.
      $view->setItemsPerPage($listing_items_per_page);
    }
  }
}

/**
 * Parse view info string into array.
 *
 * @param string $info_string
 *   Info string with view name and view display name separated by '__'.
 *
 * @return array|null
 *   View info array to alter passed by reference or NULL if incorrect string
 *   is provided. Keys are:
 *   - view_name: (string) A view machine name.
 *   - display_name: (string) A view display machine name.
 */
function _civictheme_automated_list_parse_view_info($info_string) {
  if (!is_string($info_string) || empty($info_string)) {
    return NULL;
  }

  $parts = explode('__', $info_string);

  return [
    'view_name' => $parts[0],
    'display_name' => $parts[1] ?? 'default',
  ];
}
