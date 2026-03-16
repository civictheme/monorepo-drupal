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
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $panels = [];
  /** @var \Drupal\paragraphs\Entity\Paragraph[] $panel_entities */
  $panel_entities = civictheme_get_field_value($paragraph, 'field_c_p_panels', build: $variables) ?? [];
  foreach ($panel_entities as $panel) {
    $panels[] = [
      'expanded' => civictheme_get_field_value($panel, 'field_c_p_expand'),
      'title' => civictheme_get_field_value($panel, 'field_c_p_title'),
      'content' => civictheme_get_wysiwyg_field_value($panel, 'field_c_p_content', TRUE, $theme ?? 'light'),
    ];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:accordion',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing', default: 'none'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'expand_all' => civictheme_get_field_value($paragraph, 'field_c_p_expand') ?? FALSE,
      'panels' => $panels ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}
