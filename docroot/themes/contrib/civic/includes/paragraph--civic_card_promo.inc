<?php

/**
 * @file
 * Civic card promo paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_promo(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['is_external'] = !empty($link['uri'])) ? UrlHelper::isExternal($link['uri']) : FALSE;

  // Promo date.
  if ($paragraph->hasField('field_c_p_date') && !$paragraph->get('field_c_p_date')->isEmpty()) {
    $variables['date'] = \Drupal::service('date.formatter')->format(strtotime($paragraph->get('field_c_p_date')->value), 'civic_short_date');
  }

  // Topics.
  if ($paragraph->hasField('field_c_p_topics') && !$paragraph->get('field_c_p_topics')->isEmpty()) {
    foreach ($paragraph->get('field_c_p_topics')->referencedEntities() as $topic) {
      $variables['tags'][] = $topic->label();
    }
  }
}
