<?php

/**
 * @file
 * CivicTheme Search block type.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_block().
 */
function civictheme_preprocess_block__civictheme_search(array &$variables): void {
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
  if ($link !== NULL) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['text'] = $link->get('title')->getString();
    if ($entity->hasField('field_show_mobile_menu') && civictheme_get_field_value($entity, 'field_show_mobile_menu')) {
      // Hide search block on mobile view.
      $variables['hide_on_mobile'] = TRUE;
      $search_link = [
        'url' => $link->getUrl()->toString(),
        'text' => $link->get('title')->getString(),
      ];
      _civictheme_search_link($search_link);
    }
  }

  $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
}
