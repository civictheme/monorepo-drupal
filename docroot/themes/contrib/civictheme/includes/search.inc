<?php

/**
 * @file
 * CivicTheme Search block type.
 */

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
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($entity, 'field_c_b_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['text'] = $link->get('title')->getString();
  }
}
