<?php

/**
 * @file
 * Webform theme alterations.
 */

declare(strict_types=1);

/**
 * Pre-process for Webform paragraph.
 */
function civictheme_preprocess_paragraph__civictheme_webform(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  $paragraph = $variables['paragraph'] ?? NULL;
  if ($paragraph && civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $variables['referenced_webform'] = $paragraph->get('field_c_p_webform')->view();
  }
}
