<?php

/**
 * @file
 * CivicTheme mobile menu block theme alterations.
 */

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Pre-process for Mobile Navigation block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_block__civictheme_mobile_navigation(&$variables) {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civictheme_mobile_navigation') {
    return;
  }

  $menu_fields = [
    'top_menu' => 'field_c_b_top',
    'bottom_menu' => 'field_c_b_bottom',
  ];

  // Get primary and secondary menu links by building menu tree based on
  // menu block settings.
  $cache_tags = $entity->getCacheTags();
  $cacheability = new CacheableMetadata();
  foreach ($menu_fields as $menu_key => $menu_field) {
    $menu_block = civictheme_get_field_referenced_entities($entity, $menu_field, TRUE);
    if ($menu_block) {
      if (str_starts_with($menu_block->getPluginId(), 'menu_block:')) {
        $menu_name = str_replace('menu_block:', '', $menu_block->get('settings')['id']);
        $root = empty(str_replace($menu_name . ':', '', $menu_block->get('settings')['parent'] ?? '')) ? '' : $menu_block->get('settings')['parent'];

        $menu_parameters = new MenuTreeParameters();
        // Only set max depth if the depth was provided (greater than 0).
        if ($menu_block->get('settings')['depth'] ?? 0) {
          $menu_parameters->setMaxDepth($menu_block->get('settings')['depth']);
        }
        $menu_parameters->setRoot($root);
        $menu_parameters->excludeRoot();
        $menu_tree_service = \Drupal::service('menu.link_tree');

        $tree = $menu_tree_service->load($menu_name, $menu_parameters);
        $manipulators = [
          ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
        ];
        $tree = $menu_tree_service->transform($tree, $manipulators);

        $build = $menu_tree_service->build($tree);
        $variables[$menu_key] = $build['#items'] ?? [];

        _civictheme_preprocess_menu_items($variables[$menu_key]);

        $cacheability = $cacheability->merge(CacheableMetadata::createFromRenderArray($build), $cache_tags, $menu_block->getCacheTags());
      }
    }
  }
  $cacheability->applyTo($variables);

  $variables['trigger_text'] = civictheme_get_field_value($entity, 'field_c_b_trigger_text');
  $variables['theme'] = civictheme_get_field_theme_value($entity);

  $context_theme = theme_get_setting('components.header.theme') ?? CivicthemeConstants::HEADER_THEME_DEFAULT;
  $variables['trigger_theme'] = civictheme_get_field_value($entity, 'field_c_b_trigger_theme') ?? $context_theme;
}
