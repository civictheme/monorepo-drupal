<?php

/**
 * @file
 * CivicTheme iFrame paragraph component.
 */

use Drupal\civictheme\CivicthemeUtility;
use Drupal\Core\Template\Attribute;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_iframe(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['url'] = civictheme_get_field_value($paragraph, 'field_c_p_url');
  $variables['width'] = civictheme_get_field_value($paragraph, 'field_c_p_width');
  $variables['height'] = civictheme_get_field_value($paragraph, 'field_c_p_height');
  $attributes = civictheme_get_field_value($paragraph, 'field_c_p_attributes');
  if ($attributes) {
    $variables['attributes'] = new Attribute(CivicthemeUtility::htmlAttributesToArray($attributes));
  }
}
