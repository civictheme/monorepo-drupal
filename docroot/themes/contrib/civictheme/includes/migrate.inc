<?php

/**
 * @file
 * Helper functions adding CivicTheme migration metadata.
 *
 * Information non-visible content data including theme,
 * vertical_spacing, filter options etc.
 */

use Drupal\media\MediaInterface;

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
      'field_c_n_summary' => '',
    ];
    $node = $variables['node'] ?? NULL;
    // Convert field name to attribute key and set value either node field value
    // or default value.
    foreach ($civictheme_page_default_settings as $field_name => $default_value) {
      $attribute_key = str_replace('field_c_n_', 'data-ct-migrate-', $field_name);
      $attribute_key = str_replace('_', '-', $attribute_key);
      $variables['content_attributes'][$attribute_key] = $node ? civictheme_get_field_value($node, $field_name) : $default_value;
      $variables['content_attributes'][$attribute_key] = is_bool($variables['content_attributes'][$attribute_key]) ? (int) $variables['content_attributes'][$attribute_key] : $variables['content_attributes'][$attribute_key];
    }
    // Handling edge cases - we always have a node for edge cases as these
    // are generated from field values.
    if ($node) {
      if ($variables['content_attributes']['data-ct-migrate-show-last-updated']) {
        $variables['content_attributes']['data-ct-migrate-last-updated'] = _civictheme_preprocess_civictheme_banner_get_last_updated($node);
      }

      $thumbnail = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE);
      if ($thumbnail instanceof MediaInterface) {
        $thumbnail = civictheme_media_get_url($thumbnail);
        $variables['content_attributes']['data-ct-migrate-thumbnail'] = $thumbnail;
      }

      $topics = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');
      if (!empty($topics)) {
        $topics = implode(',', $topics);
        $variables['content_attributes']['data-ct-migrate-topics'] = $topics;
      }
    }
  }
}

/**
 * Exposes civictheme migration banner metadata.
 */
function civictheme_expose_migration_metadata__banner(&$variables) {
  if (civictheme_migrate_metadata_enabled()) {
    $variables['attributes']['data-ct-migrate-banner-type'] = $variables['type'];
    $variables['attributes']['data-ct-migrate-banner-theme'] = $variables['theme'];
    $variables['attributes']['data-ct-migrate-banner-hide-breadcrumb'] = (int) empty($variables['breadcrumb']);
    if (isset($variables['background_image'])) {
      $variables['attributes']['data-ct-migrate-banner-blend-mode'] = $variables['background_image_blend_mode'];
      $variables['attributes']['data-ct-migrate-banner-background-image'] = $variables['background_image'];
    }
  }
}

/**
 * Helper to determine whether migration metadata is set to be exposed.
 */
function civictheme_migrate_metadata_enabled() {
  return (bool) theme_get_setting('components.migrate.expose_migration_metadata') ?? FALSE;
}
