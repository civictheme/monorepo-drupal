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
  $variables['url'] = civictheme_get_field_value($paragraph, 'field_c_p_url');
  $variables['width'] = civictheme_get_field_value($paragraph, 'field_c_p_width');
  $variables['height'] = civictheme_get_field_value($paragraph, 'field_c_p_height');

  if ($attributes = civictheme_get_field_value($paragraph, 'field_c_p_attributes')) {
    $variables['attributes'] = new Attribute(civictheme_parse_attributes($attributes));
  }
}
