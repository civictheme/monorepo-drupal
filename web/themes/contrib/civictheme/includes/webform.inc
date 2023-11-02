<?php

/**
 * @file
 * Webform theme alterations.
 */

/**
 * Pre-process for Webform paragraph.
 */
function civictheme_preprocess_paragraph__civictheme_webform(&$variables) {
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph && civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $variables['referenced_webform'] = $paragraph->get('field_c_p_webform')->view();
  }
}
