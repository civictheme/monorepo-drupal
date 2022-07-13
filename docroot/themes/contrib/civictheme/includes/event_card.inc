<?php

/**
 * @file
 * CivicTheme event card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_event_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Event url.
  $link = civictheme_field_has_value($paragraph, 'field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['is_external'] = !empty($link['uri']) ? UrlHelper::isExternal($link['uri']) : FALSE;

  // Event date.
  if (civictheme_field_has_value($paragraph, 'field_c_p_date')) {
    $variables['date'] = \Drupal::service('date.formatter')->format(strtotime($paragraph->get('field_c_p_date')->value), 'civictheme_short_date');
  }

  // Event topics.
  if (civictheme_field_has_value($paragraph, 'field_c_p_topic')) {
    foreach ($paragraph->get('field_c_p_topic')->referencedEntities() as $topic) {
      $variables['tags'][] = $topic->label();
    }
  }

  // Event location.
  $variables['location'] = civictheme_field_has_value($paragraph, 'field_c_p_location') ? $paragraph->get('field_c_p_location')->getString() : NULL;
}
