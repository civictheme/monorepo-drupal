<?php

/**
 * @file
 * CivicTheme Campaign paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_campaign(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  _civictheme_preprocess_paragraph__card__image($variables);

  // Image position.
  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');

  // Tags.
  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topic');

  // Summary.
  $variables['summary'] = civictheme_get_field_value($paragraph, 'field_c_p_summary', TRUE);

  // Content.
  $variables['content_bottom'] = civictheme_get_field_value($paragraph, 'field_c_p_content', TRUE);

  // Date.
  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_short_date($date->get('value')->getDateTime());
  }

  // Vertical spacing.
  $variables['vertical_spacing'] = civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing');
}
