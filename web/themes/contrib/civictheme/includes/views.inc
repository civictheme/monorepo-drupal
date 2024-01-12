<?php

/**
 * @file
 * Views alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\views\Views;

/**
 * Implements template_preprocess_views_view().
 */
function civictheme_preprocess_views_view(array &$variables): void {
  _civictheme_preprocess_views_view__view($variables);
  _civictheme_preprocess_views_view__pager($variables);
  _civictheme_preprocess_views_view__search_page($variables);
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
  }
  else {
    $variables['pager']['#attributes']['#view'] = $view;
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
 */
function civictheme_form_views_exposed_form_alter(array &$form, FormStateInterface $form_state): void {
  $storage = $form_state->getStorage();
  // Pass view ID in attributes to the exposed filter preprocessor.
  // @see civictheme_preprocess_views_exposed_form()
  if (!empty($storage['view'])) {
    $form['#attributes']['view_id'] = $storage['view']->id();
  }
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_exposed_form(array &$variables): void {
  $view = NULL;
  if (!empty($variables['form']['#attributes']['view_id'])) {
    $view = Views::getView($variables['form']['#attributes']['view_id']);
    unset($variables['form']['#attributes']['view_id']);
  }

  // Allow to opt-out from using the exposed form.
  if (_civictheme_feature_is_optedout('views', CivicthemeConstants::OPTOUT_VIEWS_EXPOSED_FILTER, $view)) {
    return;
  }

  $field_count = count(_civictheme_form__get_fields($variables['form']));

  if ($field_count == 1) {
    _civictheme_preprocess_views__exposed_form__single_filter($variables);
  }
  elseif ($field_count > 1) {
    _civictheme_preprocess_views__exposed_form__group_filter($variables);
  }
}

/**
 * Preprocess views exposed form to convert it to the Single filter.
 */
function _civictheme_preprocess_views__exposed_form__single_filter(array &$variables): void {
  $form = $variables['form'] ?? NULL;
  if (empty($form)) {
    return;
  }

  $fields = _civictheme_form__get_fields($form);
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

  $is_multiple = _civictheme_form__field_is_multiple($field);

  // Collect filter from options.
  foreach ($field['#options'] as $value => $title) {
    $attributes = [];
    $attributes['value'] = $value;
    // Build name, including handling for the multiple values.
    $attributes['name'] = $field['#name'] ? ($is_multiple ? $field['#name'] . '[]' : $field['#name']) : NULL;
    $attributes = new Attribute($attributes);

    $variables['filter_items'][] = [
      'text' => $title,
      'attributes' => (string) $attributes,
      'is_selected' => in_array($value, is_array($field['#value'] ?? []) ? $field['#value'] : [$field['#value']]),
    ];
  }

  $variables['is_multiple'] = $is_multiple;
  $variables['filter_title'] = $field['#description'] ?? t('Filter search results');
  $variables['theme'] = $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT;

  _civictheme_preprocess_form__hidden_fields($variables);

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

  $fields = _civictheme_form__get_fields($form);
  if (empty($fields)) {
    return;
  }

  $filters = [];
  foreach ($fields as $field) {
    $filter = [
      'content' => \Drupal::service('renderer')->render($field),
      'title' => $field['#title'],
    ];

    $filters[] = $filter;
  }

  $variables['filters'] = $filters;
  $variables['filter_title'] = $field['#description'] ?? t('Filter search results');
  $variables['theme'] = $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT;

  _civictheme_preprocess_form__hidden_fields($variables);

  // Mark this as a Group filter.
  $variables['group_filter'] = TRUE;
}

/**
 * Preprocess for a search page.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_views_view__search_page(array &$variables): void {
  $view = $variables['view'];
  if ($view->id() == 'civictheme_search' && $view->current_display == 'page_1') {
    $variables['vertical_spacing'] = 'top';
  }
}
