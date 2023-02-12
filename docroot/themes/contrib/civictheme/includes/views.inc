<?php

/**
 * @file
 * Views alterations.
 */

use Drupal\Core\Cache\Cache;

/**
 * Implements template_preprocess_views_view().
 */
function civictheme_preprocess_views_view(&$variables) {
  _civictheme_preprocess_views_view__pager($variables);
}

/**
 * Preprocessor for a pager.
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_views_view__pager(&$variables) {
  if (empty($variables['pager'])) {
    return;
  }

  $view = &$variables['view'];

  // Hide pager if there is only one result page.
  $view_pager = $view->display_handler->getOption('pager');
  if ($view->total_rows <= (int) $view_pager['options']['items_per_page']) {
    unset($variables['pager']);
  }
  else {
    // Attach view to pager to be available for processing.
    //
    // Mini and full pagers have different theme implementations, so we need to
    // pass the view on different properties to be accessible in the preprocess
    // functions.
    if ($view->getPager()->getPluginId() === 'mini') {
      $variables['pager']['#view'] = $view;
    }
    else {
      $variables['pager']['#attributes']['#view'] = $view;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_views_view_grid(&$variables) {
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
