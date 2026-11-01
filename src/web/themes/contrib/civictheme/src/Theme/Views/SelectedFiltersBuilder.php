<?php

declare(strict_types=1);

namespace Drupal\civictheme\Theme\Views;

use Drupal\Core\Url;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Builds selected exposed-filter chip data for views.
 *
 * Internal to CivicTheme: shared by views-view and views-exposed-form
 * preprocessors.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class SelectedFiltersBuilder {

  /**
   * Build selected filters and clear link data for a given view.
   *
   * @param \Drupal\views\ViewExecutable $view
   *   The view executable, with display set.
   *
   * @return array<string, mixed>
   *   Array with keys 'selected_filters' and 'selected_filters_clear_link'.
   */
  public static function getData(ViewExecutable $view): array {
    $selected_filters = static::buildList($view);
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
   * Build dismissible chip data for each applied exposed filter value.
   *
   * Returns one entry per submitted value of an exposed filter. Each entry
   * has the URL that drops just that value (preserving the rest of the
   * query), the human-readable filter text, and an aria-label for the
   * dismiss link.
   *
   * Query parameters that don't correspond to an exposed filter are ignored.
   *
   * @return array<string, array<string, string>>
   *   Chip data keyed by filter (or "<filter>.<index>" for multi-value
   *   filters).
   */
  public static function buildList(ViewExecutable $view): array {
    $exposed_filters = static::getFilterTypes($view);
    $request_query = \Drupal::request()->query->all();
    $base_url = Url::fromRoute('<current>')->toString();

    $chips = [];
    foreach ($request_query as $filter_id => $submitted_value) {
      if (!isset($exposed_filters[$filter_id])) {
        continue;
      }
      if (is_string($submitted_value)) {
        static::addSingle($chips, $base_url, $request_query, $filter_id, $submitted_value, $exposed_filters);
      }
      elseif (is_array($submitted_value)) {
        static::addMulti($chips, $base_url, $request_query, $filter_id, $submitted_value, $exposed_filters);
      }
    }

    return $chips;
  }

  /**
   * Append a chip for a single-value submission (text input, single select).
   */
  protected static function addSingle(array &$chips, string $base_url, array $request_query, string $filter_id, string $submitted_value, array $exposed_filters): void {
    if ($submitted_value === '') {
      return;
    }
    $remaining_query = $request_query;
    unset($remaining_query[$filter_id]);
    $chips[$filter_id] = static::createFilterUrl(
      $base_url, $remaining_query, $filter_id, $submitted_value, $exposed_filters
    );
  }

  /**
   * Append one chip per submitted value of a multi-value filter.
   *
   * Each chip is keyed by "<filter>.<index>" so it can be dismissed
   * independently of its siblings.
   */
  protected static function addMulti(array &$chips, string $base_url, array $request_query, string $filter_id, array $submitted_values, array $exposed_filters): void {
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
      $chips[$chip_key] = static::createFilterUrl(
        $base_url, $remaining_query, $filter_id, $value, $exposed_filters
      );
    }
  }

  /**
   * Create the URL for the selected filter.
   *
   * @return array<string, string>
   *   Chip data with 'url', 'text' and 'label' keys.
   */
  protected static function createFilterUrl(string $base_url, array $query_params, string $key, string|int $value, array $filter_types): array {
    $query = http_build_query($query_params);
    $text = static::createFilterLabel($key, $value, $filter_types);
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
   * @return array<string, array<string, mixed>>
   *   Array of exposed filters with their properties:
   *   - name: The human readable label
   *   - type: The filter plugin ID
   *   - machine_name: The filter ID
   */
  protected static function getFilterTypes(ViewExecutable $view): array {
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
  protected static function createFilterLabel(string $key, string|int $value, array $filter_types): string {
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

}
