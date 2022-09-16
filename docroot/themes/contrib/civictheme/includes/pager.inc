<?php

/**
 * @file
 * Pager component alterations.
 */

/**
 * Implements template_preprocess_pager().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_pager(&$variables) {
  /** @var \Drupal\Core\Pager\PagerManagerInterface $pager_manager */
  $pager_manager = \Drupal::service('pager.manager');

  $element = $variables['pager']['#element'] ?? NULL;
  if ($element === NULL) {
    return;
  }

  $pager = $pager_manager->getPager($element);

  // Set theme from civic listing component settings.
  if (isset($variables['pager']['#attributes']['#civictheme_theme'])) {
    $variables['theme'] = $variables['pager']['#attributes']['#civictheme_theme'];
    unset($variables['pager']['#attributes']['#civictheme_theme']);
  }

  if (!$pager) {
    return;
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
