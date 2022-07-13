<?php

/**
 * @file
 * CivicTheme iFrame paragraph component.
 */

use Drupal\Core\Template\Attribute;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_iframe(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['url'] = $paragraph->get('field_c_p_url')->getString();

  if (civictheme_field_has_value($paragraph, 'field_c_p_width')) {
    $variables['width'] = $paragraph->get('field_c_p_width')->getString();
  }

  if (civictheme_field_has_value($paragraph, 'field_c_p_height')) {
    $variables['height'] = $paragraph->get('field_c_p_height')->getString();
  }

  if (civictheme_field_has_value($paragraph, 'field_c_p_attributes')) {
    $variables['attributes'] = new Attribute(civictheme_parse_attributes($paragraph->get('field_c_p_attributes')->getString()));
  }

  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
}
