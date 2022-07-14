<?php

/**
 * @file
 * CivicTheme promo paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = civictheme_field_has_value($paragraph, 'field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri'])) {
    $variables['link'] = [
      'text' => $link['title'] ?? '',
      'url' => Url::fromUri($link['uri'])->toString(),
      'is_new_window' => UrlHelper::isExternal($link['uri']),
    ];
  }
  if (civictheme_field_has_value($paragraph, 'field_c_p_summary')) {
    $variables['content'] = $paragraph->get('field_c_p_summary')->getString();
  }
}
