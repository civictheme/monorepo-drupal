<?php

/**
 * @file
 * CivicTheme card promo paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['is_external'] = $link->isExternal();
  }

  // Promo date.
  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_short_date($date->get('value')->getDateTime());
  }

  // Topics.
  $topics = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  if ($topics) {
    $variables['tags'] = $topics;
  }
}
