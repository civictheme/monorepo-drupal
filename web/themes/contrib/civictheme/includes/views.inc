<?php

/**
 * @file
 * Views alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Implements template_preprocess_views_view().
 */
function civictheme_preprocess_views_view(array &$variables): void {
  _civictheme_preprocess_views_view__view($variables);
  _civictheme_preprocess_views_view__pager($variables);
  _civictheme_preprocess_views_view__search_page($variables);
  _civictheme_preprocess_views_view__selected_filters($variables);
  _civictheme_preprocess_views_view__results_count($variables);
}

/**
 * Preprocess for a view.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_views_view__view(array &$variables): void {
  /** @var \Drupal\views\ViewExecutable $view */
  $view = &$variables['view'];

  $variables['attributes']['class'] = empty($variables['dom_id']) ? Html::getUniqueId('js-view-dom-id') : 'js-view-dom-id-' . $variables['dom_id'];

  $variables['theme'] = $view->component_settings['theme'] ?? CivicthemeConstants::THEME_LIGHT;

  // @phpstan-ignore-next-line
  if (!empty($view->header) && is_array($view->header)) {
    // Extract 'results' into own variable.
    if (!empty($view->header['result'])) {
      $variables['results_count'] = $view->header['result']->render();
      unset($view->header['result']);
    }

    // Render the rest of the header.
    foreach ($view->header as $header_item) {
      $variables['rows_above'][] = $header_item->render();
    }
  }

  // Render 'exposed' as Filters component.
  if (!empty($variables['exposed'])) {
    $variables['exposed']['#attributes']['#civictheme_theme'] = $variables['theme'];
    $variables['filters'] = $variables['exposed'];
    unset($variables['exposed']);
  }
}

/**
 * Preprocess for a view pager.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_views_view__pager(array &$variables): void {
  if (empty($variables['pager'])) {
    return;
  }

  /** @var \Drupal\views\ViewExecutable $view */
  $view = &$variables['view'];

  // Hide pager if there is only one result page.
  $view_pager = $view->display_handler->getOption('pager');
  if ($view->total_rows <= (int) $view_pager['options']['items_per_page']) {
    unset($variables['pager']);
  }
  elseif ($view->getPager()->getPluginId() === 'mini') {
    // Attach view to pager to be available for processing.
    //
    // Mini and full pagers have different theme implementations, so we need to
    // pass the view on different properties to be accessible in the preprocess
    // functions.
    $variables['pager']['#view'] = $view;
    $variables['pagination'] = $variables['pager'];
  }
  else {
    $variables['pager']['#attributes']['#view'] = $view;
    $variables['pagination'] = $variables['pager'];
  }
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_view_grid(array &$variables): void {
  $view = $variables['view'];

  $component_settings = $variables['view']->component_settings ?? NULL;
  if ($component_settings) {
    // Override column count with the number of columns provided in the
    // paragraph.
    // Note that we are using 'options.columns' as this preprocess
    // may run for a standalone view page without component_settings being
    // available, so the view settings should be used instead.
    $variables['options']['columns'] = $component_settings['column_count'];
    $variables['fill_width'] = $component_settings['fill_width'];

    // Pass item_theme setting to the result items.
    foreach (array_keys($variables['rows']) as $id) {
      $variables['rows'][$id]['#node']->component_theme = $component_settings['item_theme'];
      $variables['rows'][$id]['#cache']['contexts'][] = 'url.path';
      $variables['rows'][$id]['#cache']['contexts'][] = 'url.query_args';
      $variables['rows'][$id]['#cache']['tags'] = Cache::mergeTags($component_settings['cache_tags'], $view->getCacheTags());
    }
  }
}

/**
 * Implements hook_form_views_exposed_form_alter().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_form_views_exposed_form_alter(array &$form, FormStateInterface $form_state): void {
  $storage = $form_state->getStorage();
  // Pass view ID and display ID in attributes to the exposed filter
  // preprocessor.
  // @see civictheme_preprocess_views_exposed_form()
  if (!empty($storage['view'])) {
    $form['#attributes']['view_id'] = $storage['view']->id();
    $form['#attributes']['view_display'] = $storage['view']->current_display;
  }

  // Restrict Topics exposed filter to contextual filter term(s) when present.
  $view = $form_state->get('view');
  if (!$view instanceof ViewExecutable || $view->id() !== 'civictheme_automated_list') {
    return;
  }
  if (!isset($form['topic']) || !isset($form['topic']['#type'])) {
    return;
  }
  try {
    $contextual_filter_tids = _civictheme_get_topics_contextual_argument_tids($view);
    if ($contextual_filter_tids !== []) {
      $options = _civictheme_build_topic_options_from_tids($contextual_filter_tids);
      if ($options !== []) {
        $form['topic']['#empty_option'] = t('- Any -');
        $form['topic']['#empty_value'] = '';
        $form['topic']['#options'] = $options;
      }
    }
  }
  catch (\Throwable $e) {
    \Drupal::logger('civictheme')->warning('Exposed form alter failed: @message', [
      '@message' => $e->getMessage(),
    ]);
  }
}

/**
 * Implements hook_preprocess_HOOK().
 */
function civictheme_preprocess_views_exposed_form(array &$variables): void {
  $view = _civictheme_preprocess_views_exposed_form__load_view($variables);
  _civictheme_preprocess_views_exposed_form__add_selected_filters($variables, $view);

  // Allow to opt-out from using the exposed form.
  if (_civictheme_feature_is_optedout('views', CivicthemeConstants::OPTOUT_VIEWS_EXPOSED_FILTER, $view)) {
    return;
  }

  $fields = _civictheme_form_get_form_elements($variables['form']);
  $field_count = count($fields);

  // Apply theme to exposed filter and fields.
  $theme = $variables['attributes']['#civictheme_theme'] ?? CivicthemeConstants::THEME_LIGHT;
  $variables['theme'] = $theme;
  if ($field_count > 0) {
    foreach (array_keys($fields) as $key) {
      $variables['form'][$key]['#attributes']['#civictheme_theme'] = $theme;
    }
  }

  if ($field_count == 1) {
    // Use inline filter on search and auto pages if a single text field exists.
    if (_civictheme_preprocess_views__exposed_form__inline_filter($variables, $view, $fields)) {
      return;
    }

    // Use single filter.
    _civictheme_preprocess_views__exposed_form__single_filter($variables);
  }
  elseif ($field_count > 1) {
    _civictheme_preprocess_views__exposed_form__group_filter($variables);
  }
}

/**
 * Load and prepare the view referenced by the exposed form attributes.
 *
 * The form alter hook stashes view_id and view_display on the form's
 * attributes; this helper recovers the view, sets its display, and removes
 * the temporary attributes.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_views_exposed_form__load_view(array &$variables): ?ViewExecutable {
  if (empty($variables['form']['#attributes']['view_id'])) {
    return NULL;
  }
  $view = Views::getView($variables['form']['#attributes']['view_id']);
  unset($variables['form']['#attributes']['view_id']);
  $display_id = $variables['form']['#attributes']['view_display'] ?? NULL;
  unset($variables['form']['#attributes']['view_display']);
  if (!$view instanceof ViewExecutable) {
    return NULL;
  }
  if (!empty($display_id)) {
    $view->setDisplay($display_id);
  }
  return $view;
}

/**
 * Populate selected-filter chip data on the exposed-form template variables.
 *
 * Skipped when the view uses AJAX, since dismiss URLs in the chips would not
 * trigger a re-render.
 */
function _civictheme_preprocess_views_exposed_form__add_selected_filters(array &$variables, ?ViewExecutable $view): void {
  if (!$view instanceof ViewExecutable || $view->ajaxEnabled()) {
    return;
  }
  $data = _civictheme_view_selected_filters_data($view);
  $variables['selected_filters'] = $data['selected_filters'];
  if (!empty($data['selected_filters_clear_link'])) {
    $variables['selected_filters_clear_link'] = $data['selected_filters_clear_link'];
  }
}

/**
 * Preprocess views exposed form to convert it to the Inline filter.
 *
 * @param array $variables
 *   Variables array.
 * @param \Drupal\views\ViewExecutable|null $view
 *   The view object.
 * @param array $fields
 *   Form fields.
 *
 * @return bool
 *   TRUE if inline filter was processed, FALSE otherwise.
 */
function _civictheme_preprocess_views__exposed_form__inline_filter(array &$variables, $view, array $fields): bool {
  if (!($view instanceof ViewExecutable)) {
    return FALSE;
  }

  if (!in_array($view->id(), ['civictheme_search', 'civictheme_automated_list'])) {
    return FALSE;
  }

  $keyword_field = reset($fields);
  if ($keyword_field['#type'] !== 'textfield') {
    return FALSE;
  }

  $variables['inline_filter'] = TRUE;
  $keyword_field['#title_size'] = 'extra-large';
  $variables['filter_items'] = $keyword_field;
  $submit_field = $variables['form']['actions']['submit'] ?? NULL;
  if (!empty($submit_field)) {
    $variables['submit_text'] = $submit_field['#value'] ?? '';
  }

  return TRUE;
}

/**
 * Preprocess views exposed form to convert it to the Single filter.
 */
function _civictheme_preprocess_views__exposed_form__single_filter(array &$variables): void {
  $form = $variables['form'] ?? NULL;
  if (empty($form)) {
    return;
  }

  $fields = _civictheme_form_get_form_elements($form);
  if (empty($fields)) {
    return;
  }

  $field = reset($fields);
  if (empty($field['#options'])) {
    return;
  }

  if (count($field['#options']) > CivicthemeConstants::AUTOMATED_LIST_SINGLE_FILTER_LIMIT) {
    return;
  }

  $is_multiple = _civictheme_form_element_is_multiple($field);

  // Collect filter from options.
  foreach ($field['#options'] as $value => $title) {
    $attributes = [];
    $attributes['value'] = $value;

    // Build name, including handling for the multiple values.
    $attributes['name'] = $field['#name'] ? ($is_multiple ? $field['#name'] . '[]' : $field['#name']) : NULL;
    $attributes = new Attribute($attributes);

    $variables['filter_items'][] = [
      'text' => (string) $title,
      'attributes' => $attributes,
      'is_selected' => in_array($value, is_array($field['#value'] ?? []) ? $field['#value'] : [$field['#value']]),
    ];
  }

  $variables['is_multiple'] = $is_multiple;
  $variables['filter_title'] = $field['#description'] ?? t('Filter search results');

  _civictheme_form_preprocess_hidden_form_elements($variables);

  // Mark this as a Single filter.
  $variables['single_filter'] = TRUE;
}

/**
 * Preprocess views exposed form to convert it to the Group filter.
 */
function _civictheme_preprocess_views__exposed_form__group_filter(array &$variables): void {
  $form = $variables['form'] ?? NULL;
  if (empty($form)) {
    return;
  }

  $fields = _civictheme_form_get_form_elements($form);
  if (empty($fields)) {
    return;
  }

  $filters = [];
  foreach ($fields as $field) {
    $filter = [
      'content' => $field,
      'title' => $field['#title'] ?? NULL,
    ];

    $filters[] = $filter;
  }

  $variables['filters'] = $filters;
  $variables['filter_title'] = $field['#description'] ?? t('Filter search results');

  _civictheme_form_preprocess_hidden_form_elements($variables);

  // Mark this as a Group filter.
  $variables['group_filter'] = TRUE;
}

/**
 * Preprocess views selected filters from the query params.
 */
function _civictheme_preprocess_views_view__selected_filters(array &$variables): void {
  /** @var \Drupal\views\ViewExecutable $view */
  $view = $variables['view'];

  // Selected filters does not currently support ajax.
  if ($view->ajaxEnabled()) {
    return;
  }

  $data = _civictheme_view_selected_filters_data($view);
  $variables['selected_filters'] = $data['selected_filters'];
  if (!empty($data['selected_filters_clear_link'])) {
    $variables['selected_filters_clear_link'] = $data['selected_filters_clear_link'];
  }
}

/**
 * Build selected filters and clear link data for a given view.
 *
 * Shared by views-view and views-exposed-form preprocessors.
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The view executable, with display set.
 *
 * @return array
 *   Array with keys 'selected_filters' and 'selected_filters_clear_link'.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_view_selected_filters_data(ViewExecutable $view): array {
  $selected_filters = _civictheme_preprocess_views_view__selected_filters_list($view);
  $clear_link = NULL;
  if (!empty($selected_filters)) {
    $clear_link = [
      'text' => t('Clear all'),
      'url' => Url::fromRoute('<current>')->toString(),
    ];
  }
  return [
    'selected_filters' => $selected_filters,
    'selected_filters_clear_link' => $clear_link,
  ];
}

/**
 * Preprocess for a search page.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_views_view__search_page(array &$variables): void {
  /** @var \Drupal\views\ViewExecutable $view */
  $view = $variables['view'];
  if ($view->id() == 'civictheme_search' && $view->current_display == 'page_1') {
    $variables['vertical_spacing'] = 'top';
  }
}

/**
 * Pre-process results count for views.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_views_view__results_count(array &$variables): void {
  if (empty($variables['results_count']['#markup']) || !is_string($variables['results_count']['#markup'])) {
    return;
  }
  if (!str_contains($variables['results_count']['#markup'], '@keywords')) {
    return;
  }

  // Load search fields from theme settings.
  $setting_keyword_fields = civictheme_get_theme_config_manager()->load('components.search.keyword_fields') ?? '';
  if (empty($setting_keyword_fields)) {
    // Strip the @keywords token because it exists but won't be populated.
    $variables['results_count'] = str_replace('@keywords', '', $variables['results_count']);
    return;
  }

  $variables['#cache']['contexts'] = Cache::mergeContexts($variables['#cache']['contexts'] ?? [], ['url.query_args']);

  $keywords = '';
  $exposed_input = $variables['view']->getExposedInput();
  $search_fields = CivicthemeUtility::multilineToArray($setting_keyword_fields);

  foreach ($search_fields as $field) {
    if (!empty($exposed_input[$field])) {
      $keywords = t("for '@keywords'", ['@keywords' => $exposed_input[$field]])->__toString();
      break;
    }
  }

  $variables['results_count'] = str_replace('@keywords', $keywords, $variables['results_count']);
}

/**
 * Build dismissible chip data for each applied exposed filter value.
 *
 * Returns one entry per submitted value of an exposed filter. Each entry has
 * the URL that drops just that value (preserving the rest of the query), the
 * human-readable filter text, and an aria-label for the dismiss link.
 *
 * Query parameters that don't correspond to an exposed filter are ignored.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_views_view__selected_filters_list(ViewExecutable $view): array {
  $exposed_filters = _civictheme_preprocess_views_view__selected_filters_get_filter_types($view);
  $request_query = \Drupal::request()->query->all();
  $base_url = Url::fromRoute('<current>')->toString();

  $chips = [];
  foreach ($request_query as $filter_id => $submitted_value) {
    if (!isset($exposed_filters[$filter_id])) {
      continue;
    }
    if (is_string($submitted_value)) {
      _civictheme_preprocess_views_view__selected_filters_add_single($chips, $base_url, $request_query, $filter_id, $submitted_value, $exposed_filters);
    }
    elseif (is_array($submitted_value)) {
      _civictheme_preprocess_views_view__selected_filters_add_multi($chips, $base_url, $request_query, $filter_id, $submitted_value, $exposed_filters);
    }
  }

  return $chips;
}

/**
 * Append a chip for a single-value submission (text input, single select).
 */
function _civictheme_preprocess_views_view__selected_filters_add_single(array &$chips, string $base_url, array $request_query, string $filter_id, string $submitted_value, array $exposed_filters): void {
  if ($submitted_value === '') {
    return;
  }
  $remaining_query = $request_query;
  unset($remaining_query[$filter_id]);
  $chips[$filter_id] = _civictheme_preprocess_views_view__selected_filters_list_create_filter_url(
    $base_url, $remaining_query, $filter_id, $submitted_value, $exposed_filters
  );
}

/**
 * Append one chip per submitted value of a multi-value filter.
 *
 * Each chip is keyed by "<filter>.<index>" so it can be dismissed
 * independently of its siblings.
 */
function _civictheme_preprocess_views_view__selected_filters_add_multi(array &$chips, string $base_url, array $request_query, string $filter_id, array $submitted_values, array $exposed_filters): void {
  foreach ($submitted_values as $value_index => $value) {
    if ($value === '' || $value === NULL) {
      continue;
    }
    $remaining_query = $request_query;
    unset($remaining_query[$filter_id][$value_index]);
    if (empty($remaining_query[$filter_id])) {
      unset($remaining_query[$filter_id]);
    }
    $chip_key = sprintf('%s.%s', $filter_id, $value_index);
    $chips[$chip_key] = _civictheme_preprocess_views_view__selected_filters_list_create_filter_url(
      $base_url, $remaining_query, $filter_id, $value, $exposed_filters
    );
  }
}

/**
 * Create the URL for the selected filter.
 */
function _civictheme_preprocess_views_view__selected_filters_list_create_filter_url(string $base_url, array $query_params, string $key, string|int $value, array $filter_types): array {
  $query = http_build_query($query_params);
  $text = _civictheme_preprocess_views_view__selected_filters_list__create_filter_label($key, $value, $filter_types);
  return [
    'url' => !empty($query) ? ($base_url . '?' . $query) : $base_url,
    'text' => $text,
    'label' => t('Remove filter: @filter', ['@filter' => $text])->__toString(),
  ];
}

/**
 * Get the filter types from the view.
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The view executable object.
 *
 * @return array
 *   Array of exposed filters with their properties:
 *   - name: The human readable label
 *   - type: The filter plugin ID
 *   - machine_name: The filter ID
 */
function _civictheme_preprocess_views_view__selected_filters_get_filter_types(ViewExecutable $view): array {
  $exposed_filters = [];
  $filters = $view->display_handler->getHandlers('filter');
  foreach ($filters as $filter_id => $filter) {
    if ($filter instanceof FilterPluginBase && $filter->isExposed()) {
      $exposed_filters[$filter->options['expose']['identifier']] = [
        'name' => $filter->options['expose']['label'],
        'type' => $filter->getPluginId(),
        'machine_name' => $filter_id,
      ];
    }
  }
  return $exposed_filters;
}

/**
 * Create the label correctly for each of the selected filters.
 *
 * @param string $key
 *   The filter key.
 * @param string|int $value
 *   The filter value.
 * @param array $filter_types
 *   Array of filter types containing metadata about each filter.
 *
 * @return string
 *   The formatted filter label in the format "Key: Value".
 */
function _civictheme_preprocess_views_view__selected_filters_list__create_filter_label(string $key, string|int $value, array $filter_types): string {
  switch ($filter_types[$key]['type']) {
    case 'bundle':
      $node_type = \Drupal::entityTypeManager()->getStorage('node_type')->load($value);
      $value_label = $node_type ? $node_type->label() : $value;
      break;

    case 'taxonomy_index_tid':
      $taxonomy_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($value);
      $value_label = $taxonomy_term ? $taxonomy_term->label() : $value;
      break;

    default:
      $value_label = $value;
  }
  return $filter_types[$key]['name'] . ': ' . $value_label;
}

/**
 * Gets term IDs from the Topics contextual filter (term_node_tid_depth).
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The view.
 *
 * @return int[]
 *   Term IDs from the contextual argument, or empty if none/any.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_get_topics_contextual_argument_tids(ViewExecutable $view): array {
  $display = $view->getDisplay();
  if (!method_exists($display, 'getOption')) {
    return [];
  }

  $argument_options = $display->getOption('arguments') ?? [];
  if (!is_array($argument_options)) {
    return [];
  }

  $argument_ids = array_keys($argument_options);
  $argument_index = array_search('term_node_tid_depth', $argument_ids, TRUE);
  if ($argument_index === FALSE) {
    return [];
  }

  $args = $view->args;
  if (!isset($args[$argument_index]) || $args[$argument_index] === '' || $args[$argument_index] === 'all') {
    return [];
  }

  $parts = preg_split('/[\s+]+/', (string) $args[$argument_index]);
  if ($parts === FALSE) {
    return [];
  }

  $tids = [];
  foreach ($parts as $part) {
    $part = trim($part);
    if ($part !== '' && is_numeric($part)) {
      $tids[] = (int) $part;
    }
  }

  return array_unique($tids);
}

/**
 * Builds option list for the Topics exposed filter from term IDs.
 *
 * @param int[] $tids
 *   Taxonomy term IDs (civictheme_topics).
 *
 * @return array<int|string, string>
 *   Options array keyed by term ID, value is term name.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_build_topic_options_from_tids(array $tids): array {
  if ($tids === []) {
    return [];
  }

  $terms = Term::loadMultiple($tids);
  $options = [];
  foreach ($terms as $term) {
    if ($term !== NULL) {
      $options[$term->id()] = (string) $term->label();
    }
  }

  return $options;
}
