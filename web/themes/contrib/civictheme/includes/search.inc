<?php

/**
 * @file
 * CivicTheme Search block type.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;

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
    if (civictheme_get_field_value($entity, 'field_c_b_link_in_mobile_menu')) {
      // Hide search block on mobile view.
      $variables['modifier_class'] = 'hide-xxs show-m-flex';
    }
  }

  $variables['theme'] = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
}

/**
 * Implements hook_preprocess_html().
 */
function _civictheme_preprocess_html__search_head_title(array &$variables): void {
  // Load search fields from theme settings.
  $setting_keyword_fields = civictheme_get_theme_config_manager()->load('components.search.keyword_fields') ?? '';
  if (empty($setting_keyword_fields)) {
    return;
  }

  // Add search keywords to head title.
  $keywords = NULL;
  $search_fields = CivicthemeUtility::multilineToArray($setting_keyword_fields);
  foreach ($search_fields as $field) {
    $query_field = \Drupal::request()->query->get($field);
    if (!empty($query_field)) {
      $keywords = $query_field;
      break;
    }
  }
  if (empty($keywords)) {
    return;
  }
  $head_title = (string) $variables['head_title']['title'] ?? '';
  if (empty($head_title)) {
    return;
  }
  $sanitized_keywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');
  $variables['head_title']['title'] = t("@title - Searching for '@keywords'", [
    '@title' => $head_title,
    '@keywords' => $sanitized_keywords,
  ]);
}
