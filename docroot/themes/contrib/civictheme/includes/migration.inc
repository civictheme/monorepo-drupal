<?php

/**
 * @file
 * Helper functions adding CivicTheme migration metadata.
 *
 * Information non-visible content data including theme, vertical_spacing,
 * filter options etc.
 */

/**
 * Check if the migration metadata should be exposed.
 */
function civictheme_migrate_metadata_enabled() {
  return (bool) civictheme_get_theme_config_manager()->load('migration.expose_metadata') ?? FALSE;
}

/**
 * Convert migration metadata to attributes.
 *
 * @param array $values
 *   Array of values to be converted to attributes.
 * @param string $prefix
 *   (optional) Values prefix to be added to the data attribute name.
 *
 * @return array
 *   Array of converted metadata.
 */
function _civictheme_migration_metadata_convert_to_attributes(array $values, $prefix = '') {
  $attributes = [];

  foreach ($values as $name => $value) {
    if (is_null($value)) {
      continue;
    }
    $attributes['data-ct-migrate-' . ($prefix ? trim($prefix, '-') . '-' : '') . $name] = is_scalar($value) ? (is_bool($value) ? (int) $value : $value) : json_encode($value);
  }

  return $attributes;
}

/**
 * Exposes Page level migration metadata.
 */
function _civictheme_migration_metadata__page(&$variables) {
  if (!civictheme_migrate_metadata_enabled()) {
    return;
  }

  $civictheme_page_default_settings = [
    'field_c_n_vertical_spacing' => 'none',
    'field_c_n_show_toc' => FALSE,
    'field_c_n_show_last_updated' => FALSE,
    'field_c_n_hide_sidebar' => FALSE,
    'field_c_n_summary' => '',
  ];

  $node = $variables['node'] ?? NULL;

  // Convert field name to attribute name and set value either node field value
  // or default value.
  $attributes = [];
  foreach ($civictheme_page_default_settings as $field_name => $default_value) {
    $attribute_name = str_replace('_', '-', str_replace('field_c_n_', '', $field_name));
    $attributes[$attribute_name] = $node ? civictheme_get_field_value($node, $field_name) : $default_value;
  }

  // Handling edge cases - we always have a node for edge cases as these
  // are generated from field values.
  if ($node) {
    if ($attributes['show-last-updated']) {
      $attributes['last-updated'] = _civictheme_node_get_updated_date($node);
    }

    $thumbnail = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE);
    if (!empty($thumbnail)) {
      $attributes['thumbnail'] = civictheme_media_image_get_variables($thumbnail);
    }

    $topics = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');
    if (!empty($topics)) {
      $topics = implode(',', $topics);
      $attributes['topics'] = $topics;
    }
  }

  $variables['content_attributes'] = $variables['content_attributes'] ?? [];
  $variables['content_attributes'] += _civictheme_migration_metadata_convert_to_attributes($attributes, 'node');
}

/**
 * Exposes migration Banner metadata.
 */
function _civictheme_migration_metadata__banner(&$variables) {
  if (!civictheme_migrate_metadata_enabled()) {
    return;
  }

  $attributes = [];
  $attributes['type'] = $variables['type'];
  $attributes['theme'] = $variables['theme'];
  $attributes['hide-breadcrumb'] = (int) empty($variables['breadcrumb']);

  if (!empty($variables['background_image'])) {
    $attributes['background-image-blend-mode'] = $variables['background_image_blend_mode'];
    $attributes['background-image'] = $variables['background_image'];
  }

  $variables['attributes'] = $variables['attributes'] ?? [];
  $variables['attributes'] += _civictheme_migration_metadata_convert_to_attributes($attributes, 'banner');
}
