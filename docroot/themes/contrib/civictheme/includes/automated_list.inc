<?php

/**
 * @file
 * Automated list paragraph component.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_automated_list(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Remove existing content.
  $variables['content'] = NULL;

  // The paragraph contains 2 types of values:
  // 1. Content values that should be passed as variables to the render image.
  // 2. View settings values that should be passed to a view for alterations
  // in child preprocess hooks.
  //
  // Content values.
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link_above($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link_below($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  // Views settings.
  // We want to have a single place to gather all the view settings to pass
  // them further in the pipeline. There should not be any other places to
  // access these fields variables directly (separation of concerns). Only
  // these settings should be used.
  $settings = [];
  $settings['type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_type');
  $settings['content_type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_content_type');
  $settings['limit'] = civictheme_get_field_value($paragraph, 'field_c_p_list_limit');
  $settings['limit_type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_limit_type');
  $settings['filters_exp'] = civictheme_get_field_value($paragraph, 'field_c_p_list_filters_exp', TRUE, '');
  $settings['view_as'] = civictheme_get_field_value($paragraph, 'field_c_p_list_item_view_as');
  $settings['item_theme'] = civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme');
  $settings['topics'] = civictheme_get_field_value($paragraph, 'field_c_p_list_topics');
  $settings['site_sections'] = civictheme_get_field_value($paragraph, 'field_c_p_list_site_sections');
  $settings['cache_tags'] = $paragraph->getCacheTags();
  // Use existing value getters to populate settings.
  $settings['paragraph'] = $paragraph;
  _civictheme_preprocess_paragraph__paragraph_field__theme($settings);
  _civictheme_preprocess_paragraph__paragraph_field__title($settings);
  _civictheme_preprocess_paragraph__paragraph_field__column_count($settings);
  _civictheme_preprocess_paragraph__paragraph_field__fill_width($settings);
  unset($settings['paragraph']);

  // Get view with already set display.
  /** @var \Drupal\views\ViewExecutable $view */
  $view = _civictheme_automated_list__get_view($settings);

  $settings['view_id'] = $view->id();
  $settings['view_display'] = $view->current_display;

  // Make component settings available to the processing functions to further
  // alter view settings.
  // @see civictheme_preprocess_views_view()
  $view->component_settings = $settings;

  // Preprocess view properties based on the settings above.
  _civictheme_automated_list__update_view($view);

  // Render view as rows.
  $variables['rows'] = $view->executeDisplay();

  // Add unique HTML ID.
  $attributes = [];
  $attributes['id'] = Html::getUniqueId('ct-automated-list-' . $paragraph->id());

  // Run merging of the attributes last. Do not add any code below this line.
  $variables['attributes'] = ($variables['attributes'] ?? []) + $attributes;
}

/**
 * Get view for Automated list.
 *
 * @param array $settings
 *   View alteration settings passed by reference.
 *
 * @return \Drupal\views\ViewExecutable|null
 *   Loaded view object with set display or NULL.
 *
 * @see hook_civictheme_automated_list_view_info_alter()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_automated_list__get_view(array &$settings) {
  // Get information about a view and a display that will be used produce
  // the results.
  $info = [
    'view_name' => CivicthemeConstants::AUTOMATED_LIST_DEFAULT_VIEW_NAME,
    'display_name' => CivicthemeConstants::AUTOMATED_LIST_DEFAULT_VIEW_DISPLAY_NAME,
  ];

  // Collect information about views from the 'type' setting and allow to alter
  // it in hook_civictheme_automated_list_view_info().
  if (!empty($settings)) {
    // View name and display separated by '__' in the field value.
    $parts = explode('__', $settings['type']);
    $info = [
      'view_name' => $parts[0],
      'display_name' => $parts[1] ?? 'default',
    ];

    // Allow to alter view information to modules and themes.
    \Drupal::moduleHandler()->alter('civictheme_automated_list_view_info', $info, $settings);
    \Drupal::service('theme.manager')->alter('civictheme_automated_list_view_info', $info, $settings);
  }

  // Validate that resulting information has correct structure.
  if (empty($info) || !is_array($info) || empty($info['view_name']) || empty($info['display_name'])) {
    \Drupal::logger('civictheme')->error('Missing view_name or display_name information for Automated list view.');

    return NULL;
  }

  // Get view instance.
  $view = Views::getView($info['view_name']);
  if (!$view) {
    \Drupal::logger('civictheme')->error(sprintf('Unable to use Automated list view "%s".', $info['view_name']));

    // Do not render this element if the view does not exist.
    return NULL;
  }

  // Set display.
  if (!$view->setDisplay($info['display_name'])) {
    \Drupal::logger('civictheme')->error(sprintf('Unable to use Automated list view display "%s" for view "%s".', $info['display_name'], $info['view_name']));

    // Do not render this element if the display does not exist.
    return NULL;
  }

  return $view;
}

/**
 * Loads and alters Automated list view.
 *
 * Please, do not optimise conditions in this function - the code is organised
 * this way on purpose to ease readability and debugging.
 *
 * @param \Drupal\views\ViewExecutable $view
 *   View instance.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.MissingImport)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_automated_list__update_view(ViewExecutable $view) {
  if (!isset($view->component_settings)) {
    return;
  }

  $settings = $view->component_settings;

  $view_args = [];
  // First view argument - content types.
  $view_args[] = $settings['content_type'] ?? 'all';
  // Second view argument - Topics.
  $view_args[] = !empty($settings['topics']) ? implode('+', array_keys($settings['topics'])) : 'all';
  // Third view argument - Site Sections.
  $view_args[] = !empty($settings['site_sections']) ? implode('+', array_keys($settings['site_sections'])) : 'all';
  $view->setArguments($view_args);

  // Title.
  $view->setTitle($settings['title']);

  // Set items view mode.
  // Note that resulting items (nodes) should have these view modes
  // configured and themed (we are not checking an existence of the view mode
  // for a result entity here).
  if (!empty($settings['view_as'])) {
    $view_mode_options = $view->display_handler->getOption('row');
    $view_mode_options['options']['view_mode'] = $settings['view_as'];
    $view->display_handler->setOption('row', $view_mode_options);
  }

  // @todo Implement hiding of the filters that were not selected.
  $show_filters = !empty(array_filter(explode(', ', $settings['filters_exp'])));
  if (!$show_filters) {
    $view->display_handler->has_exposed = FALSE;
  }

  // Pager.
  // Show exact number of items.
  if ($settings['limit_type'] == CivicthemeConstants::AUTOMATED_LIST_LIMIT_TYPE_LIMITED) {
    // Prevent endless lists by using a fallback limit if a limit is 0.
    $settings['limit'] = $settings['limit'] > 0 ? $settings['limit'] : CivicthemeConstants::AUTOMATED_LIST_LIMIT_FALLBACK;

    // Changing pager type to 'some' and setting 'items_per_page'.
    $view_pager = $view->display_handler->getOption('pager');
    $view_pager['type'] = 'some';
    // Set custom limit.
    $view_pager['options']['items_per_page'] = $settings['limit'];
    // Hide exposed 'items_per_page'.
    $view_pager['options']['expose']['items_per_page'] = FALSE;
    $view->display_handler->setOption('pager', $view_pager);

    // Hide 'Showing x of y' (set through a view) as this is a limited page.
    $view_header = $view->display_handler->getOption('header');
    if (!$show_filters && isset($view_header['result']['content'])) {
      $view->removeHandler($settings['view_display'], 'header', 'result');
    }
  }
  elseif ($settings['limit_type'] == CivicthemeConstants::AUTOMATED_LIST_LIMIT_TYPE_UNLIMITED) {
    if ($settings['limit'] == 0) {
      // Show pager and default number of items.
      // This is default view display state, so no change is required.
      // Do not optimise - left empty for clarity.
    }
    else {
      // Show pager with specified number of items per page.
      // Re-using existing pager.
      $view_pager = $view->display_handler->getOption('pager');
      // Set custom limit.
      $view_pager['options']['items_per_page'] = $settings['limit'];
      // Hide exposed 'items_per_page'.
      $view_pager['options']['expose']['items_per_page'] = FALSE;
      $view->display_handler->setOption('pager', $view_pager);
    }
  }

  // Mark these settings.
  $view->component_settings['processed'] = TRUE;
}
