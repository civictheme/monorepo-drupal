<?php

/**
 * @file
 * Helper functions adding CivicTheme migration metadata.
 *
 * Information non-visible content data including theme,
 * vertical_spacing, filter options etc.
 */

/**
 * Exposes civictheme page level migration metadata.
 */
function civictheme_expose_migration_metadata__page(&$variables) {
  if (civictheme_migrate_metadata_enabled()) {
    $civictheme_page_default_settings = [
      'field_c_n_vertical_spacing' => 'none',
      'field_c_n_show_toc' => FALSE,
      'field_c_n_show_last_updated' => FALSE,
      'field_c_n_hide_sidebar' => FALSE,
    ];
    $node = $variables['node'] ?? NULL;
    // Convert field name to attribute key and set value either node field value
    // or default value.
    foreach ($civictheme_page_default_settings as $field_name => $default_value) {
      $attribute_key = str_replace('field_c_n_', 'data-ct-migrate-', $field_name);
      $attribute_key = str_replace('_', '-', $attribute_key);
      $variables['content_attributes'][$attribute_key] = $node ? civictheme_get_field_value($node, 'field_c_n_vertical_spacing') : $default_value;
      $variables['content_attributes'][$attribute_key] = is_bool($variables['content_attributes'][$attribute_key]) ? (int) $variables['content_attributes'][$attribute_key] : $variables['content_attributes'][$attribute_key];
    }
  }
}

/**
 * Exposes civictheme migration banner metadata.
 */
function civictheme_expose_migration_metadata__banner(&$variables) {
  $variables['attributes']['data-ct-blend-mode'] = $variables['background_image_blend_mode'];
  $variables['attributes']['data-ct-banner-type'] = $variables['type'];
  $variables['attributes']['data-ct-banner-theme'] = $variables['theme'];
  $variables['attributes']['data-ct-show-breadcrumb'] = (int) !empty($variables['breadcrumb']);
}

/**
 * Helper to determine whether migration metadata is set to be exposed.
 */
function civictheme_migrate_metadata_enabled() {
  return (bool) theme_get_setting('components.migrate.expose_migration_metadata') ?? FALSE;
}
