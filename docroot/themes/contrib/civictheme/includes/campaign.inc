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

  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');
  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  $variables['summary'] = civictheme_get_field_value($paragraph, 'field_c_p_content', TRUE);
  $variables['vertical_spacing'] = civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing');

  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_short_date($date->get('value')->getDateTime());
  }

  $links = civictheme_get_field_value($paragraph, 'field_c_p_links');
  if ($links) {
    foreach ($links as $link) {
      $variables['links'][] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
      ];
    }
  }

}
