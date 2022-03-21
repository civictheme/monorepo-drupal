<?php

/**
 * @file
 * Civic quick links paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civic_preprocess_paragraph__civic_quick_links(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_links') && !$paragraph->get('field_c_p_links')->isEmpty()) {
    foreach ($paragraph->get('field_c_p_links')->getValue() as $link) {
      $is_external = UrlHelper::isExternal($link['uri']);
      $variables['links'][] = [
        'url' => Url::fromUri($link['uri'])->toString(),
        'text' => $link['title'],
        'is_external' => $is_external,
        'is_new_window' => $is_external,
      ];
    }
  }
}
