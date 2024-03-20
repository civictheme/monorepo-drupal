<?php

/**
 * @file
 * CivicTheme mobile menu block theme alterations.
 */

declare(strict_types=1);

use Drupal\block\BlockInterface;
use Drupal\block_content\BlockContentInterface;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Menu\MenuTreeParameters;

/**
 * Pre-process for Mobile Navigation block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function _civictheme_preprocess_block__civictheme_mobile_navigation(array &$variables): void {
  if ($variables['base_plugin_id'] != 'block_content') {
    return;
  }

  $entity = $variables['elements']['content']['#block_content'];

  if (!$entity instanceof BlockContentInterface) {
    return;
  }

  if ($entity->bundle() != 'civictheme_mobile_navigation') {
    return;
  }

  $menu_fields = [
    'top_menu' => 'field_c_b_top',
    'bottom_menu' => 'field_c_b_bottom',
  ];

  // Get primary and secondary menu links by building menu tree based on
  // menu block settings.
  $entity_cache_tags = $entity->getCacheTags();
  $cacheability = new CacheableMetadata();
  foreach ($menu_fields as $menu_key => $menu_field) {
    $menu_block = civictheme_get_field_referenced_entity($entity, $menu_field);

    if (!$menu_block instanceof BlockInterface) {
      continue;
    }

    if (str_starts_with($menu_block->getPluginId(), 'menu_block:')) {
      $block_settings = (array) $menu_block->get('settings');

      $menu_name = str_replace('menu_block:', '', (string) $block_settings['id']);
      $root = empty(str_replace($menu_name . ':', '', $block_settings['parent'] ?? '')) ? '' : $block_settings['parent'];

      $expand_all_items = $block_settings['expand_all_items'] ?? FALSE;
      $menu_parameters = new MenuTreeParameters();
      if (!$expand_all_items) {
        $menu_tree = \Drupal::menuTree();
        $menu_parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
      }
      // Only set max depth if the depth was provided (greater than 0).
      if ($block_settings['depth'] ?? 0) {
        $menu_parameters->setMaxDepth($block_settings['depth']);
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

      _civictheme_preprocess_menu_items($variables[$menu_key], $expand_all_items);

      $cacheability = $cacheability->merge(CacheableMetadata::createFromRenderArray($build));
      $cacheability = $cacheability->addCacheTags($entity_cache_tags);
      $cacheability = $cacheability->addCacheTags($menu_block->getCacheTags());
    }
  }

  $cacheability->applyTo($variables);

  $variables['trigger_text'] = civictheme_get_field_value($entity, 'field_c_b_trigger_text');
  $variables['theme'] = civictheme_get_field_theme_value($entity);

  $context_theme = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['trigger_theme'] = civictheme_get_field_value($entity, 'field_c_b_trigger_theme') ?? $context_theme;
}
