<?php

/**
 * @file
 * Civic event card paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_event(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Event url.
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  $variables['url'] = $link['uri'] ?? NULL;

  // Event date.
  if ($paragraph->hasField('field_c_p_date') && !$paragraph->get('field_c_p_date')->isEmpty()) {
    $variables['date'] = \Drupal::service('date.formatter')->format($paragraph->get('field_c_p_date')->date->getTimestamp(), 'civic_short_date');
  }

  // Event topics.
  if ($paragraph->hasField('field_c_p_topic') && !$paragraph->get('field_c_p_topic')->isEmpty()) {
    foreach ($paragraph->get('field_c_p_topic')->referencedEntities() as $topic) {
      $variables['tags'][] = $topic->label();
    }
  }
}
