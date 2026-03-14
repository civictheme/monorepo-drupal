<?php

/**
 * @file
 * CivicTheme Slider paragraph component.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Xss;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_slider(array &$variables): void {
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  // Slides are rendered as child paragraphs via entity reference field.
  $slides = NULL;
  if (!empty($variables['content']['field_c_p_slides'])) {
    $slides = $variables['content']['field_c_p_slides'];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:slider',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => (bool) civictheme_get_field_value($paragraph, 'field_c_p_background'),
      'previous_label' => t('Previous'),
      'next_label' => t('Next'),
      'slides' => $slides,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph|null $paragraph */
  $paragraph = $variables['paragraph'] ?? NULL;
  if ($paragraph === NULL || !$paragraph instanceof Paragraph) {
    return;
  }

  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $heading_level = 2;
  $parent = $paragraph->getParentEntity();
  if ($parent instanceof Paragraph && $parent->bundle() === 'civictheme_slider') {
    $heading_level = civictheme_field_has_value($parent, 'field_c_p_title') ? 3 : 2;
  }

  $image = NULL;
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE, build: $variables);
  if ($media) {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::SLIDER_SLIDE_IMAGE_STYLE);
  }

  $tags = array_map(
    static fn($label) => ['content' => (string) $label],
    civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics', build: $variables)
  );

  $date_props = [];
  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime(), 'civictheme_short_date');
    $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
  }

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
    '#component' => 'civictheme:slide',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'image_position' => civictheme_get_field_value($paragraph, 'field_c_p_image_position'),
      'tags' => $tags ?: NULL,
      'links' => $links ?: NULL,
      'heading_level' => $heading_level,
      'modifier_class' => $modifier_class,
    ] + $date_props, fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
    ]),
  ];
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide_ref(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph|null $paragraph */
  $paragraph = $variables['paragraph'] ?? NULL;
  if ($paragraph === NULL || !$paragraph instanceof Paragraph) {
    return;
  }

  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $heading_level = 2;
  $parent = $paragraph->getParentEntity();
  if ($parent instanceof Paragraph && $parent->bundle() === 'civictheme_slider') {
    $heading_level = civictheme_field_has_value($parent, 'field_c_p_title') ? 3 : 2;
  }

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE, build: $variables);
  if (empty($node)) {
    return;
  }

  $image = NULL;
  $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE, build: $variables);
  if ($media instanceof MediaInterface && $media->getSource()->getPluginId() === 'image') {
    $image = civictheme_media_image_get_variables($media, CivicthemeConstants::SLIDER_SLIDE_IMAGE_STYLE);
  }

  $tags = array_map(
    static fn($label) => ['content' => (string) $label],
    civictheme_get_referenced_entity_labels($node, 'field_c_n_topics', $variables)
  );

  $date_props = [];
  if ($node->bundle() == 'civictheme_event') {
    $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
    if ($date) {
      $date_props['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
      $date_props['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
      if ($date->get('end_value')->getDateTime()) {
        $date_props['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
        $date_props['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
      }
    }
  }
  else {
    $date_props['date'] = civictheme_format_datetime($node->getChangedTime(), 'civictheme_short_date');
    $date_props['date_iso'] = civictheme_format_datetime_iso($node->getChangedTime());
  }

  // Use node summary as slide content.
  $content_slot = NULL;
  $summary = civictheme_get_field_value($node, 'field_c_n_summary', TRUE);
  if (!empty($summary)) {
    $summary_text = _civictheme_truncate_summary($summary, $paragraph->bundle());
    if ($summary_text) {
      $content_slot = ['#markup' => $summary_text];
    }
  }

  $links = [];
  $link_text = civictheme_get_field_value($paragraph, 'field_c_p_link_text', TRUE);
  if ($link_text) {
    $links[] = [
      'text' => $link_text,
      'url' => $node->toUrl()->toString(),
      'is_external' => $node->toUrl()->isExternal(),
      'is_new_window' => $node->toUrl()->isExternal(),
    ];
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:slide',
    '#props' => array_filter([
      'theme' => $theme,
      'image' => $image,
      'image_position' => civictheme_get_field_value($paragraph, 'field_c_p_image_position'),
      'tags' => $tags ?: NULL,
      'links' => $links ?: NULL,
      'heading_level' => $heading_level,
      'modifier_class' => $modifier_class,
    ] + $date_props, fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $node->getTitle() ? ['#markup' => $node->getTitle()] : NULL,
      'content' => $content_slot,
    ]),
  ];
}
