<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_accordion(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['expand_all'] = (bool) $paragraph->get('field_c_p_expand')->getString();
  $variables['panels'] = [];
  foreach ($paragraph->get('field_c_p_panels') as $panel_reference) {
    $paragraph_id = $panel_reference->getValue()['target_id'];
    $panel_paragraph = Paragraph::load($paragraph_id);
    $variables['panels'][] = [
      'expanded' => (bool) $panel_paragraph->get('field_c_p_expand')->getString(),
      'title' => $panel_paragraph->get('field_c_p_title')->getString(),
      'content' => $panel_paragraph->get('field_c_p_content')->view(),
    ];
  }
}
