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
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $referenced_webform = NULL;
  if (civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $referenced_webform = $paragraph->get('field_c_p_webform')->view(['label' => 'hidden']);
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:webform',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'referenced_webform' => $referenced_webform,
    ]),
  ];
}
