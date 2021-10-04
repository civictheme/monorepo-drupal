<?php

/**
 * @file
 * Civic Card container paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_container(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['column_count'] = $paragraph->hasField('field_c_p_column_count') ? $paragraph->get('field_c_p_column_count')->getString() : '';
  $variables['fill_width'] = $paragraph->hasField('field_c_p_fill_width') ? (bool) $paragraph->get('field_c_p_fill_width')->getString() : FALSE;
  $link = $paragraph->hasField('field_c_p_link') ? $paragraph->get('field_c_p_link')->getValue() : [];
  $variables['link_text'] = $link['title'] ?? NULL;
  $variables['url'] = $link['url'] ?? NULL;
  $variables['cards'] = array_filter($variables['content']['field_c_p_cards'], function ($key) {
    return is_numeric($key);
  }, ARRAY_FILTER_USE_KEY);
}
