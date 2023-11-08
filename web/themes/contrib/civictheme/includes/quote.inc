<?php

/**
 * @file
 * CivicTheme Quote paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_quote(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);

  $variables['author'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_author');
}
