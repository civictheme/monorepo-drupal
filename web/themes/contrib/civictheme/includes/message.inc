<?php

/**
 * @file
 * CivicTheme Message paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_message(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  $variables['type'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_message_type');
  $variables['has_aria'] = FALSE;
}
