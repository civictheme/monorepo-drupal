<?php

/**
 * @file
 * CivicTheme Callout paragraph component.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Xss;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_callout(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $links = [];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem[]|null $link_items */
  $link_items = civictheme_get_field_value($paragraph, 'field_c_p_links');
  if ($link_items) {
    foreach ($link_items as $link) {
      $links[] = [
        'text' => Xss::filter($link->get('title')->getString()),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:callout',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'links' => $links ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
    ]),
  ];
}
