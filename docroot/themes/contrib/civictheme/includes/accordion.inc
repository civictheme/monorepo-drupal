<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_accordion(&$variables) {
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__field__background($variables);

  $panels = civictheme_get_field_value($variables['paragraph'], 'field_c_p_panels') ?? [];
  foreach ($panels as $panel) {
    $variables['panels'][] = [
      'expanded' => civictheme_get_field_value($panel, 'field_c_p_expand'),
      'title' => civictheme_get_field_value($panel, 'field_c_p_title'),
      'content' => $panel->field_c_p_content->view(),
    ];
  }

  $variables['expand_all'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_expand') ?? FALSE;
}
