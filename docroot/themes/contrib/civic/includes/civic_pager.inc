<?php

/**
 * @file
 * Page component alterations.
 */

/**
 * Implements template_preprocess_pager().
 */
function civic_preprocess_pager(&$variables) {
  /** @var \Drupal\Core\Pager\PagerManagerInterface $pager_manager */
  $pager_manager = \Drupal::service('pager.manager');
  $element = $variables['pager']['#element'];
  $pager = $pager_manager->getPager($element);

  // Add required class for ajax pagination.
  $variables['pager_items_modifier_class'] = 'js-pager__items';

  if (isset($variables['pager']['#tags'])) {
    // Drupal seems to want to post link to first and last page
    // and the same link in the items - we only want to print the link once.
    $named_pages = [
      'first',
      'last',
    ];
    foreach ($named_pages as $named_page) {
      if (!empty($variables['items'][$named_page])) {
        $page_exists = (bool) array_filter($variables['items']['pages'], function ($page) use ($variables, $named_page) {
          return ($page['href'] === $variables['items'][$named_page]['href']);
        });
        // We remove duplicated pagination link.
        if ($page_exists) {
          unset($variables['items'][$named_page]);
        }
        else {
          $variables['items'][$named_page]['text'] = $named_page === 'last' ? $pager->getTotalPages() : 1;
        }
      }
    }
  }
}
