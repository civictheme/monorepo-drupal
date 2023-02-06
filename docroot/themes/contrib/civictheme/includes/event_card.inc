<?php

/**
 * @file
 * CivicTheme event card paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_event_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'] ?? NULL;
  if (!$paragraph) {
    return;
  }

  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
  }

  $date_range = civictheme_get_field_value($paragraph, 'field_c_p_date_range', TRUE);
  if ($date_range) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date_range->get('value')->getDateTime(), $date_range->get('end_value')->getDateTime());
  }

  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  $variables['location'] = civictheme_get_field_value($paragraph, 'field_c_p_location');
}
