<?php

/**
 * @file
 * CivicTheme mobile menu block theme alterations.
 */

use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Pre-process for Mobile Navigation block.
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
    'top_menu' => 'field_c_b_top_menu',
    'bottom_menu' => 'field_c_b_bottom_menu',
  ];

  // Get primary and secondary menu links by building menu tree based on
  // menu block settings.
  $cache_tags = $entity->getCacheTags();
  $cacheability = new CacheableMetadata();
  foreach ($menu_fields as $menu_key => $menu_field) {
    if (
      $entity->hasField($menu_field)
      && !$entity->get($menu_field)->isEmpty()
      && !empty($entity->get($menu_field)->referencedEntities()[0])
      && strpos($entity->get($menu_field)->referencedEntities()[0]->getPluginId(), 'menu_block') === 0
    ) {
      $menu_block = $entity->get($menu_field)->referencedEntities()[0];
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
  $cacheability->applyTo($variables);

  if ($entity->hasField('field_c_b_trigger_text') && !$entity->get('field_c_b_trigger_text')->isEmpty()) {
    $variables['trigger_text'] = $entity->get('field_c_b_trigger_text')->getString();
  }

  if ($entity->hasField('field_c_b_theme') && !$entity->get('field_c_b_theme')->isEmpty()) {
    $variables['theme'] = strtolower($entity->get('field_c_b_theme')->getString());
  }

  if ($entity->hasField('field_c_b_trigger_theme') && !$entity->get('field_c_b_trigger_theme')->isEmpty()) {
    $variables['trigger_theme'] = strtolower($entity->get('field_c_b_trigger_theme')->getString());
  }
}
