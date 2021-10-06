<?php

/**
 * @file
 * Civic Accordion paragraph component.
 */

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_accordion(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $accordion_title = $paragraph->get('field_c_p_title')->first();
  $variables['title'] = !empty($accordion_title) ? $accordion_title->getString() : NULL;
  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
  $variables['expand_all'] = (bool) $paragraph->get('field_c_p_expand')->getString();
  $variables['panels'] = [];
  foreach ($paragraph->get('field_c_p_panels') as $panel_reference) {
    $paragraph_id = $panel_reference->getValue()['target_id'];
    $panel_paragraph = Paragraph::load($paragraph_id);
    $variables['panels'][] = [
      'expanded' => (bool) $panel_paragraph->get('field_c_p_expand')->getString(),
      'title' => $panel_paragraph->get('field_c_p_title')->getString(),
      'content' => $panel_paragraph->get('field_c_p_content')->getValue()[0]['value'],
    ];
  }
}
