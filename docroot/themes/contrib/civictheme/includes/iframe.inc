<?php

/**
 * @file
 * CivicTheme iFrame paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_iframe(&$variables) {
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__field__background($variables);

  $variables['url'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_url');
  $variables['width'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_width');
  $variables['height'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_height');
  $variables['component_attributes'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_attributes');
}
