<?php

/**
 * @file
 * Civic Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_map(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['embed_url'] = $paragraph->get('field_c_p_embed_url')->getString();
  $variables['address'] = $paragraph->get('field_c_p_address')->getString();
  if (!$paragraph->get('field_c_p_share_link')->isEmpty()) {
    $variables['share_link'] = $paragraph->get('field_c_p_share_link')->getString();
  }
  if (!$paragraph->get('field_c_p_view_link')->isEmpty()) {
    $variables['view_link'] = $paragraph->get('field_c_p_view_link')->getString();
  }
  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
}
