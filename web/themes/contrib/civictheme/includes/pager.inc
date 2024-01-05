<?php

/**
 * @file
 * Pager component alterations.
 */

declare(strict_types=1);

use Drupal\Core\Pager\Pager;
use Drupal\views\ViewExecutable;

/**
 * Implements template_preprocess_pager().
 */
function civictheme_preprocess_pager(array &$variables): void {
  _civictheme_preprocess_pager($variables);
}

/**
 * Implements template_preprocess_views_mini_pager().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_mini_pager(array &$variables): void {
  _civictheme_preprocess_pager($variables);
}

/**
 * Preprocess pager.
 *
 * Handles all types of pagers.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_preprocess_pager(array &$variables): void {
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

  // @see _civictheme_preprocess_views_view__pager()
  $view = $variables['attributes']['#view'] ?? $variables['view'] ?? NULL;
  unset($variables['attributes']['#view']);
  unset($variables['view']);
  if ($view) {
    $component_settings = $view->component_settings ?? NULL;
    // Set theme from CivicTheme Automated list component settings.
    if ($component_settings) {
      $variables['theme'] = $component_settings['theme'];
    }

    _civictheme_preprocess_pager__items_per_page($variables, $view, $pager);

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
function _civictheme_preprocess_pager__items_per_page(array &$variables, ViewExecutable $view, Pager $pager): void {
  $view_pager = $view->getPager();
  if (empty($view_pager->options['expose']['items_per_page']) || $view_pager->options['expose']['items_per_page'] !== TRUE) {
    return;
  }

  // Label from the view settings.
  $variables['items_per_page_label'] = $view_pager->options['expose']['items_per_page_label'];

  // List of items per page from the view settings.
  $items_per_page = [];
  $items_per_page_options = explode(',', (string) $view_pager->options['expose']['items_per_page_options']);
  $current_items_per_page = (int) $pager->getLimit();
  foreach ($items_per_page_options as $value) {
    $items_per_page[] = [
      'type' => 'option',
      'label' => $value,
      'value' => (int) $value,
      'selected' => (int) $value === $current_items_per_page,
    ];
  }

  $variables['items_per_page_options'] = $items_per_page;

  // Render an exposed form and add attributes to the select.
  /** @var \Drupal\views\Plugin\views\exposed_form\ExposedFormPluginInterface $plugin */
  $plugin = $view->display_handler->getPlugin('exposed_form');
  $exposed_widgets = $plugin->renderExposedForm();
  $variables['items_per_page_attributes'] = 'name="items_per_page" form="' . $exposed_widgets['#id'] . '"';
}
