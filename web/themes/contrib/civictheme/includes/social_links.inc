<?php

/**
 * @file
 * CivicTheme Social Links block theme alterations.
 */

/**
 * Pre-process for Social Links block.
 */
function _civictheme_preprocess_block__civictheme_social_links(&$variables) {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civictheme_social_links' || !civictheme_field_has_value($entity, 'field_c_b_social_icons')) {
    return;
  }

  $items = [];

  /** @var \Drupal\paragraphs\Entity\Paragraph[] $paragraphs |null */
  $social_link_paragraphs = civictheme_get_field_referenced_entities($entity, 'field_c_b_social_icons') ?? [];
  foreach ($social_link_paragraphs as $social_link_paragraph) {
    $item = [];

    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = civictheme_get_field_value($social_link_paragraph, 'field_c_p_link', TRUE);
    if ($link) {
      $item['url'] = $link->getUrl()->toString();
      $item['title'] = $link->get('title')->getString();
    }

    $media = civictheme_get_field_value($social_link_paragraph, 'field_c_p_icon', TRUE);
    if ($media) {
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
