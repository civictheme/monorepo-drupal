<?php

/**
 * @file
 * Primary navigation block theme alterations.
 */

use Drupal\block\Entity\Block;

/**
 * Implements template_preprocess_block__HOOK().
 */
function civic_preprocess_block__menu_block__civic_primary_navigation(&$variables) {
  $variables['theme'] = theme_get_setting('civic_header_theme') ?? 'light';
  $variables['items'] = $variables['content']['#items'];
  $variables['title'] = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  $variables['modifier_class'] = 'civic-justify-content-end';

  $block = Block::load($variables['elements']['#id']);
  $region = $block->getRegion();
  if (strpos($region, 'sidebar') === 0) {
    $variables['in_sidebar'] = TRUE;
  }
}
