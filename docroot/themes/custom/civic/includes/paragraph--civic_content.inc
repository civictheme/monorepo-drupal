<?php

/**
 * @file
 * Civic Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_content(&$variables) {
  _civic_preprocess_paragraph($variables);
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $content = ($paragraph->hasField('field_c_p_content') && !$paragraph->get('field_c_p_content')->isEmpty()) ? $paragraph->get('field_c_p_content')->first()->getValue() : NULL;
  unset($variables['content']);
  if (isset($content)) {
    $variables['content'] = $content['value'];
  }
}
