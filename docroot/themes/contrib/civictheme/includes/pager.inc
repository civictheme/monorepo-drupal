<?php

/**
 * @file
 * Pager component alterations.
 */

/**
 * Implements template_preprocess_pager().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_preprocess_pager(&$variables) {
  /** @var \Drupal\Core\Pager\PagerManagerInterface $pager_manager */
  $pager_manager = \Drupal::service('pager.manager');
  $element = $variables['pager']['#element'] ?? NULL;
  if ($element === NULL) {
    return;
  }

  // Set theme from CivicTheme Automated list component settings.
  if (isset($variables['pager']['#attributes']['#civictheme_theme'])) {
    $variables['theme'] = $variables['pager']['#attributes']['#civictheme_theme'];
    unset($variables['pager']['#attributes']['#civictheme_theme']);
  }

  $pager = $pager_manager->getPager($element);
  if (!$pager) {
    return;
  }
  $view = $variables['attributes']['#view'] ?? NULL;
  if ($view) {
    unset($variables['attributes']['#view']);
    _civictheme_preprocess_pager__items_per_page($variables, $view, $pager);
  }

  $variables['total_pages'] = $pager->getTotalPages();
  $variables['current'] = $variables['current'] ?? $pager->getCurrentPage() + 1;

  if ($variables['total_pages'] > 1 && !empty($variables['pager']['#tags'])) {
    // Add in previous and next view defined text with defined tag keys.
    // @see \Drupal\views\Plugin\views\pager\Full::render().
    $controls = [
      1 => 'previous',
      3 => 'next',
    ];
    foreach ($controls as $tag_key => $control) {
      if (!isset($variables['items'][$control]) && !empty($variables['pager']['#tags'][$tag_key])) {
        $variables['items'][$control] = [
          'text' => $variables['pager']['#tags'][$tag_key],
        ];
      }
    }
  }

  // Add CSS class for ajax pagination.
  $variables['pager_items_modifier_class'] = 'js-pager__items';
}

/**
 * Implements template_preprocess_views_mini_pager().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_mini_pager(&$variables) {
  /** @var \Drupal\Core\Pager\PagerManagerInterface $pager_manager */
  $pager_manager = \Drupal::service('pager.manager');
  if (isset($variables['items']['current'])) {
    $variables['items']['pages'] = [
      $variables['items']['current'] => [
        'href' => '#',
      ],
    ];
  }

  $element = $variables['element'] ?? NULL;
  if ($element === NULL) {
    return;
  }

  $pager = $pager_manager->getPager($element);
  if (!$pager) {
    return;
  }

  $variables['total_pages'] = $pager->getTotalPages();
  $variables['current'] = $variables['current'] ?? $pager->getCurrentPage() + 1;

  $view = $variables['view'];
  if ($view) {
    unset($variables['view']);
    _civictheme_preprocess_pager__items_per_page($variables, $view, $pager);
  }

  _civictheme_preprocess_mini_pager__ellipsis($variables);
}

/**
 * Helper to preprocess items_per_page in pagination component.
 */
function _civictheme_preprocess_pager__items_per_page(&$variables, $view, $pager) {
  /** @var \Drupal\views\ViewExecutable $view */
  $view_pager = $view->getPager();
  if (!empty($view_pager->options['expose']['items_per_page']) && $view_pager->options['expose']['items_per_page'] === TRUE) {
    $options = $view_pager->options;
    $variables['items_per_page_label'] = $options['expose']['items_per_page_label'];
    $current_items_per_page = (int) $pager->getLimit();
    unset($variables['attributes']['#view']);
    $exposed_form = $view->display_handler->getPlugin('exposed_form');
    $exposed_widgets = $exposed_form->renderExposedForm();
    $items_per_page = [];
    $items_per_page_options = explode(',', $view_pager->options['expose']['items_per_page_options']);
    foreach ($items_per_page_options as $value) {
      $items_per_page[] = [
        'type' => 'option',
        'label' => $value,
        'value' => (int) $value,
        'selected' => (int) $value === $current_items_per_page,
      ];
    }
    $variables['items_per_page_options'] = $items_per_page;
    $variables['items_per_page_attributes'] = 'name="items_per_page" form="' . $exposed_widgets['#id'] . '"';
  }
}

/**
 * Helper to process ellipsis for mini pager.
 */
function _civictheme_preprocess_mini_pager__ellipsis(&$variables) {
  if ($variables['items']['current'] > 1) {
    $variables['ellipses']['previous'] = TRUE;
  }

  if ($variables['items']['current'] < $variables['total_pages']) {
    $variables['ellipses']['next'] = TRUE;
  }
}
