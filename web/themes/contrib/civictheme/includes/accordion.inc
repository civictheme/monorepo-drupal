<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_accordion(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  /** @var \Drupal\paragraphs\Entity\Paragraph[] $panels */
  $panels = civictheme_get_field_value($variables['paragraph'], 'field_c_p_panels') ?? [];
  foreach ($panels as $panel) {
    $panel_variables = [
      'expanded' => civictheme_get_field_value($panel, 'field_c_p_expand'),
      'title' => civictheme_get_field_value($panel, 'field_c_p_title'),
    ];
    $panel_variables['paragraph'] = $panel;
    _civictheme_preprocess_paragraph__paragraph_field__content($panel_variables);

    $variables['panels'][] = $panel_variables;
  }

  $variables['expand_all'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_expand') ?? FALSE;
}
