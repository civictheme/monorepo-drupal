<?php

/**
 * @file
 * CivicTheme Social Links block theme alterations.
 */

/**
 * Defines Large banner style.
 */
define('CIVICTHEME_SOCIAL_LINKS_DEFAULT_THEME', 'dark');

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

  /** @var \Drupal\paragraphs\Entity\Paragraph[] $paragraphs */
  $paragraphs = $entity->field_c_b_social_icons->referencedEntities();

  $items = [];
  if (!empty($paragraphs)) {
    foreach ($paragraphs as $paragraph) {
      $item = [];
      $link = civictheme_field_has_value($paragraph, 'field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
      $item['url'] = $link['uri'] ?? NULL;
      $item['title'] = $link['title'] ?? NULL;
      $item['size'] = 'regular';

      if (!isset($items['image'])) {
        $item['paragraph'] = $paragraph;
        _civictheme_preprocess_paragraph__card__icon($item);
        unset($item['paragraph']);
      }

      if (isset($item['image'])) {
        // Embed SVG as HTML content by passing as text.
        $item['text'] = civictheme_embed_svg($item['image']['src'], [
          'civictheme-icon',
          'civictheme-icon--size-' . $item['size'],
        ]);
      }
      $items[] = $item;
    }
  }
  $variables['items'] = $items;

  $variables['theme'] = civictheme_get_theme_value($entity) ?? CIVICTHEME_SOCIAL_LINKS_DEFAULT_THEME;

  $variables['with_border'] = civictheme_field_has_value($entity, 'field_c_b_with_border') ? (bool) $entity->get('field_c_b_with_border')->getString() : FALSE;
}
