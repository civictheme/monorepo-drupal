<?php

/**
 * @file
 * Civic card promo paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_promo(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  $variables['link_text'] = $link['title'] ?? NULL;
  $variables['url'] = $link['url'] ?? NULL;

  if ($paragraph->hasField('field_c_p_date') && !$paragraph->get('field_c_p_date')->isEmpty()) {
    $variables['date'] = \Drupal::service('date.formatter')->format(strtotime($paragraph->get('field_c_p_date')->value), 'civic_short_date');
  }
}
