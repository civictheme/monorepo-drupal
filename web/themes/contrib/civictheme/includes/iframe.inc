<?php

/**
 * @file
 * CivicTheme iFrame paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_iframe(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $width = civictheme_get_field_value($paragraph, 'field_c_p_width');
  $height = civictheme_get_field_value($paragraph, 'field_c_p_height');

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:iframe',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'url' => civictheme_get_field_value($paragraph, 'field_c_p_url'),
      'width' => !empty($width) ? (int) $width : NULL,
      'height' => !empty($height) ? (int) $height : NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}
