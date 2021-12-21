<?php

/**
 * @file
 * Civic event card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_event(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Event url.
  $link = ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['is_external'] = !empty($link['uri'])) ? UrlHelper::isExternal($link['uri']) : FALSE;

  // Event date.
  if ($paragraph->hasField('field_c_p_date') && !$paragraph->get('field_c_p_date')->isEmpty()) {
    $variables['date'] = \Drupal::service('date.formatter')->format(strtotime($paragraph->get('field_c_p_date')->value), 'civic_short_date');
  }

  // Event topics.
  if ($paragraph->hasField('field_c_p_topic') && !$paragraph->get('field_c_p_topic')->isEmpty()) {
    foreach ($paragraph->get('field_c_p_topic')->referencedEntities() as $topic) {
      $variables['tags'][] = $topic->label();
    }
  }

  // Event location.
  $variables['location'] = ($paragraph->hasField('field_c_p_location') && !$paragraph->get('field_c_p_location')->isEmpty()) ? $paragraph->get('field_c_p_location')->getString() : NULL;
}
