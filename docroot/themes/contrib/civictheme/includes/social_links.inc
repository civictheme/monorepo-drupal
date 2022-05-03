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
  if ($entity->bundle() != 'civictheme_social_links' || ($entity->hasField('field_c_b_social_icons') && $entity->field_c_b_social_icons->isEmpty())) {
    return;
  }

  /** @var \Drupal\paragraphs\Entity\Paragraph[] $paragraphs */
  $paragraphs = $entity->field_c_b_social_icons->referencedEntities();

  $items = [];
  if (!empty($paragraphs)) {
    foreach ($paragraphs as $paragraph) {
      $vars = [];
      $link = $paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty() ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
      $vars['url'] = $link['uri'] ?? NULL;
      $vars['title'] = $link['title'] ?? NULL;
      $vars['size'] = 'regular';

      if (!isset($items['image'])) {
        $vars['paragraph'] = $paragraph;
        _civictheme_preprocess_paragraph__card__icon($vars);
        unset($vars['paragraph']);
      }

      if (isset($vars['image'])) {
        // Embed SVG as HTML content by passing as text.
        $vars['text'] = civictheme_embed_svg($vars['image']['src'], [
          'civictheme-icon',
          'civictheme-icon--size-' . $vars['size'],
        ]);
      }
      $items[] = $vars;
    }
  }
  $variables['items'] = $items;

  $variables['theme'] = CIVICTHEME_SOCIAL_LINKS_DEFAULT_THEME;
  if ($entity->hasField('field_c_b_theme') && !$entity->get('field_c_b_theme')->isEmpty()) {
    $variables['theme'] = strtolower($entity->get('field_c_b_theme')->getString());
  }

  $variables['with_border'] = $entity->hasField('field_c_b_with_border') ? (bool) $entity->get('field_c_b_with_border')->getString() : FALSE;
}
