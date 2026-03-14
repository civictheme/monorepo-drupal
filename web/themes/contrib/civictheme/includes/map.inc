<?php

/**
 * @file
 * CivicTheme Map paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_map(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $url = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_embed_url', TRUE);
  if ($link !== NULL) {
    $url = $link->getUrl()->toString();
  }

  $view_url = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $view_link */
  $view_link = civictheme_get_field_value($paragraph, 'field_c_p_view_link', TRUE);
  if ($view_link !== NULL) {
    $view_url = $view_link->getUrl()->toString();
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:map',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'url' => $url,
      'address' => civictheme_get_field_value($paragraph, 'field_c_p_address', default: '') ?: NULL,
      'view_url' => $view_url,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}
