<?php

/**
 * @file
 * CivicTheme Social Links block theme alterations.
 */

declare(strict_types=1);

use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Pre-process for Social Links block.
 */
function _civictheme_preprocess_block__civictheme_social_links(array &$variables): void {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civictheme_social_links' || !civictheme_field_has_value($entity, 'field_c_b_social_icons')) {
    return;
  }

  $items = [];

  $paragraphs = civictheme_get_field_referenced_entities($entity, 'field_c_b_social_icons');
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

    $media = civictheme_get_field_value($paragraph, 'field_c_p_icon', TRUE);
    if ($media instanceof MediaInterface) {
      $image = civictheme_media_image_get_variables($media);
      if ($image) {
        $item['icon_html'] = civictheme_embed_svg($image['url'], [
          'ct-icon',
        ]);
      }
    }

    $items[] = $item;
  }

  $variables['items'] = $items;

  $variables['theme'] = civictheme_get_field_theme_value($entity);
  $variables['with_border'] = civictheme_get_field_value($entity, 'field_c_b_with_border') ?? FALSE;
}
