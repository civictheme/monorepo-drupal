<?php

/**
 * @file
 * Civic Service card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_service_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_links') && !$paragraph->get('field_c_p_links')->isEmpty()) {
    foreach ($paragraph->get('field_c_p_links')->getValue() as $link) {
      $is_external = UrlHelper::isExternal($link['uri']);
      if (!$is_external) {
        $link['uri'] = Url::fromUri($link['uri'])->toString();
      }
      $variables['links'][] = [
        'url' => $link['uri'],
        'text' => $link['title'],
        'is_external' => $is_external,
        'new_window' => $is_external,
      ];
    }
  }
}
