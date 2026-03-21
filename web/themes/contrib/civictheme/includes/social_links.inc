<?php

/**
 * @file
 * CivicTheme Social Links block theme alterations.
 */

declare(strict_types=1);

use Drupal\Core\Render\Markup;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_block__HOOK().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__civictheme_social_links(array &$variables): void {
  $entity = $variables['elements']['content']['#block_content'] ?? NULL;
  if (!$entity || !civictheme_field_has_value($entity, 'field_c_b_social_icons')) {
    return;
  }

  $items = [];

  $paragraphs = civictheme_get_field_referenced_entities($entity, 'field_c_b_social_icons', $variables);
  foreach ($paragraphs as $paragraph) {
    if (!$paragraph instanceof Paragraph) {
      continue;
    }

    $item = [];

    $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
    if ($link instanceof LinkItem) {
      $item['url'] = $link->getUrl()->toString();
      $item['title'] = $link->get('title')->getString();
    }

    $media = civictheme_get_field_value($paragraph, 'field_c_p_icon', TRUE, build: $variables);
    if ($media instanceof MediaInterface) {
      $image = civictheme_media_image_get_variables($media);
      if ($image) {
        $svg = civictheme_embed_svg($image['url'], ['ct-icon']);
        $item['icon_html'] = $svg !== NULL ? Markup::create($svg) : '';
      }
    }

    $items[] = $item;
  }

  $theme = civictheme_get_field_theme_value($entity);
  $with_border = civictheme_get_field_value($entity, 'field_c_b_with_border') ?? FALSE;
  $modifier_class = civictheme_extract_modifier_class($variables);

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:social-links',
    '#props' => array_filter([
      'theme' => $theme,
      'with_border' => $with_border,
      'items' => $items ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
  ];
}
