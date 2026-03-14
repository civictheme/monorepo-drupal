<?php

/**
 * @file
 * CivicTheme Next step paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_next_step(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $link = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_item */
  $link_item = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link_item !== NULL) {
    $link = [
      'url' => $link_item->getUrl()->toString(),
      'is_new_window' => $link_item->isExternal(),
      'is_external' => $link_item->isExternal(),
    ];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:next-step',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'link' => $link,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
    ]),
  ];
}
