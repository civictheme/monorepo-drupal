<?php

/**
 * @file
 * CivicTheme Search block type.
 */

use Drupal\Core\Url;

/**
 * Implements template_preprocess_block().
 */
function civictheme_preprocess_block__civictheme_search(&$variables) {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civictheme_search' || ($entity->hasField('field_c_b_link') && $entity->field_c_b_link->isEmpty())) {
    return;
  }

  $variables['content'] = NULL;

  $link = $entity->get('field_c_b_link')->first()->getValue();
  $variables['url'] = Url::fromUri($link['uri'])->toString();
  $variables['text'] = $link['title'] ?? '';
}
