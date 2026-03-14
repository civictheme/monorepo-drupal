<?php

/**
 * @file
 * Automated list paragraph component.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_automated_list(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  // Extract content values for the list component.
  $link_above = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_above_item */
  $link_above_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_above', TRUE);
  if ($link_above_item !== NULL) {
    $link_above = [
      'text' => $link_above_item->get('title')->getString(),
      'url' => $link_above_item->getUrl()->toString(),
      'is_new_window' => $link_above_item->isExternal(),
      'is_external' => $link_above_item->isExternal(),
    ];
  }

  $link_below = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_below_item */
  $link_below_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_below', TRUE);
  if ($link_below_item !== NULL) {
    $link_below = [
      'text' => $link_below_item->get('title')->getString(),
      'url' => $link_below_item->getUrl()->toString(),
      'is_new_window' => $link_below_item->isExternal(),
      'is_external' => $link_below_item->isExternal(),
    ];
  }

  // Views settings.
  // We want to have a single place to gather all initial view settings to pass
  // them further in the pipeline. There should not be any other places to
  // access these fields variables directly (separation of concerns). Only
  // these settings should be used.
  // Consumer sites can alter these settings in
  // hook_civictheme_automated_list_view_alter().
  $settings = [];
  // Store paragraph entity in the settings to allow accessing the values of
  // fields that may be added in the consumer sites (in addition to the fields
  // provided by the CivicTheme Automated List paragraph).
  $settings['paragraph'] = $paragraph;
  $settings['cache_tags'] = $paragraph->getCacheTags();
  // Settings provided by the CivicTheme Automated List paragraph's fields.
  $settings['type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_type');
  $settings['content_type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_content_type');
  $settings['limit'] = civictheme_get_field_value($paragraph, 'field_c_p_list_limit');
  $settings['limit_type'] = civictheme_get_field_value($paragraph, 'field_c_p_list_limit_type');
  $settings['filters_exp'] = civictheme_get_field_value($paragraph, 'field_c_p_list_filters_exp', TRUE, '');
  $settings['view_as'] = civictheme_get_field_value($paragraph, 'field_c_p_list_item_view_as');
  $settings['item_theme'] = civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme');
  $settings['topics'] = civictheme_get_field_value($paragraph, 'field_c_p_list_topics', build: $variables);
  $settings['site_sections'] = civictheme_get_field_value($paragraph, 'field_c_p_list_site_sections', build: $variables);
  $settings['theme'] = $theme;
  _civictheme_preprocess_paragraph__paragraph_field__theme($settings);
  _civictheme_preprocess_paragraph__paragraph_field__title($settings);
  _civictheme_preprocess_paragraph__paragraph_field__column_count($settings);
  _civictheme_preprocess_paragraph__paragraph_field__fill_width($settings);

  // Get view with already set display.
  try {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = _civictheme_automated_list__get_view($settings);
  }
  catch (\Exception $e) {
    \Drupal::logger('civictheme')->error($e->getMessage());
    return;
  }

  $settings['view_id'] = $view->id();
  $settings['view_display'] = $view->current_display;

  // Make component settings available to the processing functions to further
  // alter view settings.
  // @see civictheme_preprocess_views_view()
  // @phpstan-ignore-next-line
  $view->component_settings = $settings;

  // Preprocess view properties based on the settings above.
  _civictheme_automated_list__update_view($view);

  // Render view as rows.
  $rows = $view->executeDisplay();

  // Include the CSS class from the view display in modifier_class.
  $css_class = $view->getDisplay()->getOption('css_class');
  if (!empty($css_class)) {
    $modifier_class = trim(($modifier_class ?? '') . ' ' . $css_class) ?: NULL;
  }

  // Add unique HTML ID via wrapper attributes.
  $attributes = $variables['attributes'] ?? [];
  $attributes['id'] = Html::getUniqueId('ct-automated-list-' . $paragraph->id());

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:list',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'link_above' => $link_above,
      'link_below' => $link_below,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
      'rows' => $rows,
    ]),
  ];

  // Preserve attributes for the wrapper.
  $variables['attributes'] = $attributes;
}

/**
 * Get view for Automated list.
 *
 * @param array $settings
 *   View alteration settings passed by reference.
 *
 * @return \Drupal\views\ViewExecutable
 *   Loaded view object with set display.
 *
 * @throws \Exception
 *   If view information is missing or view is not found.
 *
 * @see hook_civictheme_automated_list_view_info_alter()
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_automated_list__get_view(array &$settings): ViewExecutable {
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
    $parts = explode('__', (string) $settings['type']);
    $info = [
      'view_name' => $parts[0],
      'display_name' => $parts[1] ?? 'default',
    ];

    // Allow modules and themes to alter view information.
    \Drupal::moduleHandler()->alter('civictheme_automated_list_view_info', $info, $settings);
    \Drupal::service('theme.manager')->alter('civictheme_automated_list_view_info', $info, $settings);
  }

  // Validate that resulting information has correct structure.
  if (empty($info) || !is_array($info) || empty($info['view_name']) || empty($info['display_name'])) {
    throw new \Exception('Missing view_name or display_name information for Automated list view.');
  }

  // Get view instance.
  $view = Views::getView($info['view_name']);
  if ($view === NULL) {
    throw new \Exception(sprintf('Unable to use Automated list view "%s".', $info['view_name']));
  }

  // Set display.
  if (!$view->setDisplay($info['display_name'])) {
    throw new \Exception(sprintf('Unable to use Automated list view display "%s" for view "%s".', $info['display_name'], $info['view_name']));
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
function _civictheme_automated_list__update_view(ViewExecutable $view): void {
  if (!property_exists($view, 'component_settings') || $view->component_settings === NULL) {
    return;
  }

  $settings = $view->component_settings;

  $view_args = [];
  // First view argument - content types.
  $view_args[] = $settings['content_type'] ?? 'all';
  // Second view argument - Topics.
  $view_args[] = empty($settings['topics']) ? 'all' : implode('+', array_keys($settings['topics']));
  // Third view argument - Site Sections.
  $view_args[] = empty($settings['site_sections']) ? 'all' : implode('+', array_keys($settings['site_sections']));
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

  // Exposed filters.
  $exposed_filters = array_filter(explode(', ', $settings['filters_exp'] ?? ''));
  $show_filters = !empty($exposed_filters);
  if ($show_filters) {
    // Disable filters based on the component settings.
    $view_filters = $view->display_handler->getOption('filters');
    foreach ($view_filters as $view_filter_id => $view_filter) {
      if (!empty($view_filter['exposed']) && !in_array($view_filter['expose']['identifier'], $exposed_filters)) {
        unset($view_filters[$view_filter_id]);
      }
    }

    $view->display_handler->setOption('filters', $view_filters);
  }
  else {
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

  // Allow modules and themes to alter view based on settings.
  \Drupal::moduleHandler()->alter('civictheme_automated_list_view', $view);
  \Drupal::service('theme.manager')->alter('civictheme_automated_list_view', $view);

  // Mark these settings.
  $view->component_settings['processed'] = TRUE;
}
