<?php

/**
 * @file
 * CivicTheme Campaign paragraph component.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_campaign(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::CAMPAIGN_IMAGE_STYLE);
  }

  $tags = array_map(
    static fn($label) => ['content' => (string) $label],
    civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics', build: $variables)
  );

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

  $date_value = NULL;
  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $date_value = civictheme_format_datetime($date->get('value')->getDateTime(), 'civictheme_short_date');
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:campaign',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'image_position' => civictheme_get_field_value($paragraph, 'field_c_p_image_position'),
      'tags' => $tags ?: NULL,
      'links' => $links ?: NULL,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'date' => $date_value,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
    ]),
  ];
}
