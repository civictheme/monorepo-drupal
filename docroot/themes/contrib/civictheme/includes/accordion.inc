<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_accordion(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['expand_all'] = civictheme_get_field_value($paragraph, 'field_c_p_expand') ?? FALSE;
  $variables['panels'] = [];
  $panels = civictheme_get_field_value($paragraph, 'field_c_p_panels') ?? [];
  foreach ($panels as $panel) {
    $variables['panels'][] = [
      'expanded' => civictheme_get_field_value($panel, 'field_c_p_expand'),
      'title' => civictheme_get_field_value($panel, 'field_c_p_title'),
      'content' => $panel->field_c_p_content->view(),
    ];
  }
}
