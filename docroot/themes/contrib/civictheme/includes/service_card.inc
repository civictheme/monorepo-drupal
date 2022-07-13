<?php

/**
 * @file
 * CivicTheme Service card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_service_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if (civictheme_field_has_value($paragraph, 'field_c_p_links')) {
    foreach ($paragraph->get('field_c_p_links')->getValue() as $link) {
      $is_external = UrlHelper::isExternal($link['uri']);
      if (!$is_external) {
        $link['uri'] = Url::fromUri($link['uri'])->toString();
      }
      $variables['links'][] = [
        'url' => $link['uri'],
        'text' => $link['title'],
        'is_external' => $is_external,
        'is_new_window' => $is_external,
      ];
    }
  }
}
