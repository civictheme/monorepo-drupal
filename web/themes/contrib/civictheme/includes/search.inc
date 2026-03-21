<?php

/**
 * @file
 * CivicTheme Search block type.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;

/**
 * Implements template_preprocess_block__HOOK().
 */
function civictheme_preprocess_block__civictheme_search(array &$variables): void {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civictheme_search' || ($entity->hasField('field_c_b_link') && $entity->field_c_b_link->isEmpty())) {
    return;
  }

  $theme = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $modifier_class = civictheme_extract_modifier_class($variables);

  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($entity, 'field_c_b_link', TRUE);
  $url = NULL;
  $text = NULL;
  if ($link !== NULL) {
    $url = $link->getUrl()->toString();
    $text = $link->get('title')->getString();
    if (civictheme_get_field_value($entity, 'field_c_b_link_in_mobile_menu')) {
      $modifier_class = trim('hide-xxs show-m-flex ' . ($modifier_class ?? ''));
    }
  }

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:search',
    '#props' => array_filter([
      'theme' => $theme,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'url' => $url ? ['#markup' => $url] : NULL,
      'text' => $text ? ['#markup' => $text] : NULL,
    ]),
  ];
}

/**
 * Implements hook_preprocess_html().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
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
  $head_title = (string) ($variables['head_title']['title'] ?? '');
  if (empty($head_title)) {
    return;
  }
  $variables['head_title']['title'] = t("@title - Searching for '@keywords'", [
    '@title' => $head_title,
    '@keywords' => $keywords,
  ]);
}
