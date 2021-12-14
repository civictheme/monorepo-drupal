<?php

/**
 * @file
 * Civic Social link paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_social_icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['title'] = $link['title'] ?? NULL;

  if (!isset($variables['image'])) {
    _civic_preprocess_paragraph_card__icon($variables);
  }
  if (isset($variables['image'])) {
    $variables['content'] = [
      '#url' => $variables['image']['src'],
      '#theme' => 'image',
      '#uri' => $variables['image']['src'],
      '#alt' => $variables['image']['alt'],
      '#title' => $variables['image']['title'],
      '#width' => '24',
    ];
  }
}
