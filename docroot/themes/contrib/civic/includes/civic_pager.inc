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

  $variables['total_pages'] = $pager->getTotalPages();
  $variables['current'] = $variables['current'] ?? $pager->getCurrentPage() + 1;
  if ($variables['total_pages'] > 1) {
    // Add in previous and next view defined text with defined tag keys.
    // @see \Drupal\views\Plugin\views\pager\Full::render().
    $controls = [
      1 => 'previous',
      3 => 'next',
    ];
    foreach ($controls as $tag_key => $control) {
      if (!isset($variables['items'][$control])) {
        $variables['items'][$control] = [
          'text' => $variables['pager']['#tags'][$tag_key],
        ];
      }
    }
  }
}
