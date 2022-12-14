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

  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_short_date($date->get('value')->getDateTime());
  }

  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topic');
  $variables['location'] = civictheme_get_field_value($paragraph, 'field_c_p_location');
}
