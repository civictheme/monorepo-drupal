<?php

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Menu\MenuTreeParameters;

function _civic_preprocess_civic_mobile_menu(&$variables) {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];
  if ($entity->bundle() != 'civic_mobile_menu') {
    return;
  }
  $menu_fields = [
    'primary_navigation_items' => 'field_c_b_primary_menu',
    'secondary_navigation_items' => 'field_c_b_secondary_menu',
  ];

  // Get primary and secondary menu links by building menu tree based on
  // menu block settings.
  $cache_tags = $entity->getCacheTags();
  foreach ($menu_fields as $menu_key => $menu_field) {
    if ($entity->hasField($menu_field) && !$entity->get($menu_field)->isEmpty() && strpos($entity->get($menu_field)
        ->referencedEntities()[0]->getPluginId(), 'menu_block') === 0) {
      $menu_block = $entity->get($menu_field)->referencedEntities()[0];
      $menu_name = str_replace('menu_block:', '', $menu_block->get('settings')['id']);
      $root = empty(str_replace($menu_name . ':', '', $menu_block->get('settings')['parent'])) ? '' : $menu_block->get('settings')['parent'];
      $menu_parameters = new MenuTreeParameters();
      $menu_parameters->setMaxDepth($menu_block->get('settings')['depth']);
      $menu_parameters->setRoot($root);
      $menu_parameters->excludeRoot();
      $menu_tree_service = \Drupal::service('menu.link_tree');
      $tree = $menu_tree_service->load($menu_name, $menu_parameters);
      $variables[$menu_key] = $menu_tree_service->build($tree)['#items'];
      $cache_tags = Cache::mergeTags($cache_tags, $menu_block->getCacheTags());
    }
  }
  $variables['theme'] = 'light';
  $variables['#cache']['tags'] = $cache_tags;
}
