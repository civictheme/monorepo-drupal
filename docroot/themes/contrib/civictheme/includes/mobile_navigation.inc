<?php

/**
 * @file
 * CivicTheme mobile menu block theme alterations.
 */

use Drupal\Core\Cache\Cache;
use Drupal\Core\Menu\MenuTreeParameters;

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
  foreach ($menu_fields as $menu_key => $menu_field) {
    if (
      $entity->hasField($menu_field)
      && !$entity->get($menu_field)->isEmpty()
      && !empty($entity->get($menu_field)->referencedEntities()[0])
      && strpos($entity->get($menu_field)->referencedEntities()[0]->getPluginId(), 'menu_block') === 0
    ) {
      $menu_block = $entity->get($menu_field)->referencedEntities()[0];
      $menu_name = str_replace('menu_block:', '', $menu_block->get('settings')['id']);
      $root = empty(str_replace($menu_name . ':', '', $menu_block->get('settings')['parent'])) ? '' : $menu_block->get('settings')['parent'];
      $menu_parameters = new MenuTreeParameters();
      $menu_parameters->setMaxDepth($menu_block->get('settings')['depth']);
      $menu_parameters->setRoot($root);
      $menu_parameters->excludeRoot();
      $menu_tree_service = \Drupal::service('menu.link_tree');
      $tree = $menu_tree_service->load($menu_name, $menu_parameters);
      $manipulators = [
        ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
      ];
      $tree = $menu_tree_service->transform($tree, $manipulators);
      $variables[$menu_key] = $menu_tree_service->build($tree)['#items'];
      $cache_tags = Cache::mergeTags($cache_tags, $menu_block->getCacheTags());
    }
  }
  $variables['#cache']['tags'] = $cache_tags;

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
