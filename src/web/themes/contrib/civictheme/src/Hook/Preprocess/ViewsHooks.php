<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\civictheme\Theme\FeatureOptout;
use Drupal\civictheme\Theme\Form\FormElementHelper;
use Drupal\civictheme\Theme\Views\SelectedFiltersBuilder;
use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Pager\Pager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Template\Attribute;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Views and pager preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 */
class ViewsHooks {

  use StringTranslationTrait;

  /**
   * Implements template_preprocess_views_view().
   */
  #[Hook('preprocess_views_view')]
  public function preprocessViewsView(array &$variables): void {
    $this->preprocessView($variables);
    $this->preprocessViewPager($variables);
    $this->preprocessViewSearchPage($variables);
    $this->preprocessViewSelectedFilters($variables);
    $this->preprocessViewResultsCount($variables);
  }

  /**
   * Implements hook_preprocess_HOOK() for views_view_grid.
   */
  #[Hook('preprocess_views_view_grid')]
  public function preprocessViewsViewGrid(array &$variables): void {
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
   * Implements hook_preprocess_HOOK() for views_exposed_form.
   */
  #[Hook('preprocess_views_exposed_form')]
  public function preprocessViewsExposedForm(array &$variables): void {
    $view = $this->loadExposedFormView($variables);
    $this->addExposedFormSelectedFilters($variables, $view);

    // Allow to opt-out from using the exposed form.
    if (FeatureOptout::isOptedOut('views', CivicthemeConstants::OPTOUT_VIEWS_EXPOSED_FILTER, $view)) {
      return;
    }

    $fields = FormElementHelper::getFormElements($variables['form']);
    $field_count = count($fields);

    // Apply theme to exposed filter and fields.
    $theme = $variables['attributes']['#civictheme_theme'] ?? CivicthemeConstants::THEME_LIGHT;
    $variables['theme'] = $theme;
    if ($field_count > 0) {
      foreach (array_keys($fields) as $key) {
        $variables['form'][$key]['#attributes']['#civictheme_theme'] = $theme;
      }
    }

    if ($field_count === 1) {
      // Use inline filter on search and auto pages if a single text field
      // exists.
      if ($this->exposedFormInlineFilter($variables, $view, $fields)) {
        return;
      }

      // Use single filter.
      $this->exposedFormSingleFilter($variables);
    }
    elseif ($field_count > 1) {
      $this->exposedFormGroupFilter($variables);
    }
  }

  /**
   * Implements template_preprocess_pager().
   */
  #[Hook('preprocess_pager')]
  public function preprocessPager(array &$variables): void {
    $this->preprocessPagerGeneric($variables);
  }

  /**
   * Implements template_preprocess_views_mini_pager().
   */
  #[Hook('preprocess_views_mini_pager')]
  public function preprocessViewsMiniPager(array &$variables): void {
    $this->preprocessPagerGeneric($variables);
  }

  /**
   * Preprocess for a view.
   */
  protected function preprocessView(array &$variables): void {
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
   * @SuppressWarnings("PHPMD.ElseExpression")
   */
  protected function preprocessViewPager(array &$variables): void {
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
      // Mini and full pagers have different theme implementations, so we
      // need to pass the view on different properties to be accessible in
      // the preprocess functions.
      $variables['pager']['#view'] = $view;
      $variables['pagination'] = $variables['pager'];
    }
    else {
      $variables['pager']['#attributes']['#view'] = $view;
      $variables['pagination'] = $variables['pager'];
    }
  }

  /**
   * Preprocess for a search page.
   */
  protected function preprocessViewSearchPage(array &$variables): void {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];
    if ($view->id() == 'civictheme_search' && $view->current_display == 'page_1') {
      $variables['vertical_spacing'] = 'top';
    }
  }

  /**
   * Preprocess views selected filters from the query params.
   */
  protected function preprocessViewSelectedFilters(array &$variables): void {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];

    // Selected filters does not currently support ajax.
    if ($view->ajaxEnabled()) {
      return;
    }

    $data = SelectedFiltersBuilder::getData($view);
    $variables['selected_filters'] = $data['selected_filters'];
    if (!empty($data['selected_filters_clear_link'])) {
      $variables['selected_filters_clear_link'] = $data['selected_filters_clear_link'];
    }
  }

  /**
   * Pre-process results count for views.
   */
  protected function preprocessViewResultsCount(array &$variables): void {
    if (empty($variables['results_count']['#markup']) || !is_string($variables['results_count']['#markup'])) {
      return;
    }
    if (!str_contains($variables['results_count']['#markup'], '@keywords')) {
      return;
    }

    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    // Load search fields from theme settings.
    $setting_keyword_fields = $theme_config_manager->load('components.search.keyword_fields') ?? '';
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
        $keywords = $this->t("for '@keywords'", ['@keywords' => $exposed_input[$field]])->__toString();
        break;
      }
    }

    $variables['results_count'] = str_replace('@keywords', $keywords, $variables['results_count']);
  }

  /**
   * Load and prepare the view referenced by the exposed form attributes.
   *
   * The form alter hook stashes view_id and view_display on the form's
   * attributes; this helper recovers the view, sets its display, and removes
   * the temporary attributes.
   */
  protected function loadExposedFormView(array &$variables): ?ViewExecutable {
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
   * Populate selected-filter chip data on the exposed-form variables.
   *
   * Skipped when the view uses AJAX, since dismiss URLs in the chips would
   * not trigger a re-render.
   */
  protected function addExposedFormSelectedFilters(array &$variables, ?ViewExecutable $view): void {
    if (!$view instanceof ViewExecutable || $view->ajaxEnabled()) {
      return;
    }
    $data = SelectedFiltersBuilder::getData($view);
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
  protected function exposedFormInlineFilter(array &$variables, ?ViewExecutable $view, array $fields): bool {
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
  protected function exposedFormSingleFilter(array &$variables): void {
    $form = $variables['form'] ?? NULL;
    if (empty($form)) {
      return;
    }

    $fields = FormElementHelper::getFormElements($form);
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

    $is_multiple = FormElementHelper::isMultiple($field);

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
    $variables['filter_title'] = $field['#description'] ?? $this->t('Filter search results');

    FormElementHelper::preprocessHiddenFormElements($variables);

    // Mark this as a Single filter.
    $variables['single_filter'] = TRUE;
  }

  /**
   * Preprocess views exposed form to convert it to the Group filter.
   */
  protected function exposedFormGroupFilter(array &$variables): void {
    $form = $variables['form'] ?? NULL;
    if (empty($form)) {
      return;
    }

    $fields = FormElementHelper::getFormElements($form);
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
    $variables['filter_title'] = $field['#description'] ?? $this->t('Filter search results');

    FormElementHelper::preprocessHiddenFormElements($variables);

    // Mark this as a Group filter.
    $variables['group_filter'] = TRUE;
  }

  /**
   * Preprocess pager.
   *
   * Handles all types of pagers.
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   */
  protected function preprocessPagerGeneric(array &$variables): void {
    $element = $variables['pager']['#element'] ?? $variables['element'] ?? NULL;
    if ($element === NULL) {
      return;
    }

    $pager = \Drupal::service('pager.manager')->getPager($element);
    if (!$pager) {
      return;
    }

    // Allow to set 'previous' and 'next' text from view settings.
    if ($pager->getTotalPages() > 1 && !empty($variables['pager']['#tags'])) {
      // For tag keys, @see \Drupal\views\Plugin\views\pager\Full::render().
      $controls = [
        1 => 'previous',
        3 => 'next',
      ];
      foreach ($controls as $tag_key => $control_name) {
        if (!isset($variables['items'][$control_name]) && !empty($variables['pager']['#tags'][$tag_key])) {
          $variables['items'][$control_name] = [
            'text' => $variables['pager']['#tags'][$tag_key],
          ];
        }
      }
    }

    // Set expected variables.
    $variables['total_pages'] = $pager->getTotalPages();
    $variables['current'] = $variables['current'] ?? $pager->getCurrentPage() + 1;
    // Add CSS class for Drupal ajax pagination support.
    $variables['items_modifier_class'] = 'js-pager__items';

    // @see self::preprocessViewPager()
    $view = $variables['attributes']['#view'] ?? $variables['view'] ?? NULL;
    unset($variables['attributes']['#view']);
    unset($variables['view']);
    if ($view) {
      $component_settings = $view->component_settings ?? NULL;
      // Set theme from CivicTheme Automated list component settings.
      if ($component_settings) {
        $variables['theme'] = $component_settings['theme'];
      }

      $this->preprocessPagerItemsPerPage($variables, $view, $pager);

      $pager_type = $view->getPager()->getPluginId();
      if ($pager_type == 'mini') {
        $variables['use_ellipsis'] = TRUE;

        // Force showing the current page.
        if (isset($variables['items']['current'])) {
          $variables['items']['pages'] = [
            $variables['items']['current'] => [
              'href' => '#',
            ],
          ];
        }
      }
    }
  }

  /**
   * Helper to preprocess items_per_page in pagination component.
   */
  protected function preprocessPagerItemsPerPage(array &$variables, ViewExecutable $view, Pager $pager): void {
    $view_pager = $view->getPager();
    if (empty($view_pager->options['expose']['items_per_page']) || $view_pager->options['expose']['items_per_page'] !== TRUE) {
      return;
    }

    // Label from the view settings.
    $variables['items_per_page_title'] = $view_pager->options['expose']['items_per_page_label'];

    // List of items per page from the view settings.
    $items_per_page = [];
    $items_per_page_options = explode(',', (string) $view_pager->options['expose']['items_per_page_options']);
    $current_items_per_page = (int) $pager->getLimit();
    foreach ($items_per_page_options as $value) {
      $items_per_page[] = [
        'type' => 'option',
        'label' => $value,
        'value' => $value,
        'selected' => (int) $value === $current_items_per_page,
      ];
    }

    $variables['items_per_page_options'] = $items_per_page;

    // Render an exposed form and add attributes to the select.
    /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $plugin */
    $plugin = $view->display_handler->getPlugin('exposed_form');
    $exposed_widgets = $plugin->renderExposedForm();

    $variables['items_per_page_name'] = 'items_per_page';
    $variables['items_per_page_id'] = $exposed_widgets['#id'] ?? 0;
    $variables['items_per_page_attributes'] = new Attribute(['form' => $exposed_widgets['#id'] ?? '0']);
  }

}
