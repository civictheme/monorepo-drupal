<?php

/**
 * @file
 * Civic Accordion paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_search(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['button_text'] = $paragraph->get('field_c_p_button_text')->getString();
  $variables['helper_text'] = $paragraph->get('field_c_p_help_text')->getString();
  $variables['placeholder'] = $paragraph->get('field_c_p_placeholder')->getString();
  $search_url = $paragraph->get('field_c_p_search_url')->getString();

  // Check if the url is valid.
  if (UrlHelper::isValid($search_url)) {
    $variables['search_url'] = $search_url;
  }

  // Add query string if available.
  $keys = \Drupal::request()->query->get('keys');
  if (!empty($keys)) {
    $variables['keys'] = $keys;
  }

}
