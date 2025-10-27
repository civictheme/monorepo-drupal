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
  $entity = $variables['elements']['content']['#block_content'] ?? NULL;
  if (!$entity instanceof BlockContentInterface || $entity->bundle() !== 'civictheme_mobile_navigation') {
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
    $menu_block = civictheme_get_field_referenced_entity($entity, $menu_field, $variables);

    if (!$menu_block instanceof BlockInterface || !str_starts_with($menu_block->getPluginId(), 'menu_block:')) {
      continue;
    }

    $block_settings = $menu_block->get('settings');

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
    _civictheme_preprocess_top_mobile_menu($variables, $menu_key);
    _civictheme_preprocess_menu_items($variables[$menu_key], $expand_all_items);

    $cacheability = $cacheability->merge(CacheableMetadata::createFromRenderArray($build));
    $cacheability->addCacheTags($entity_cache_tags);
    $cacheability->addCacheTags($menu_block->getCacheTags());
  }

  $cacheability->applyTo($variables);

  $variables['trigger_text'] = civictheme_get_field_value($entity, 'field_c_b_trigger_text');
  $variables['theme'] = civictheme_get_field_theme_value($entity);

  $context_theme = civictheme_get_theme_config_manager()->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);
  $variables['trigger_theme'] = civictheme_get_field_value($entity, 'field_c_b_trigger_theme') ?? $context_theme;
}

/**
 * Additional preprocessing of the mobile top menu.
 *
 * @param array $variables
 *   Preprocess variables.
 * @param string $menu_key
 *   Menu key.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_top_mobile_menu(array &$variables, string $menu_key): void {
  if ($menu_key !== 'top_menu') {
    return;
  }
  // Add search links to end of top menu.
  $search_links = _civictheme_get_mobile_search_links();
  $cacheable_metadata = CacheableMetadata::createFromRenderArray($variables);
  $cacheable_metadata = $cacheable_metadata->merge(CacheableMetadata::createFromRenderArray($search_links));
  $cacheable_metadata->applyTo($variables);
  foreach ($search_links['items'] as $search_link) {
    $variables[$menu_key][] = [
      'url' => $search_link['url'],
      'title' => $search_link['text'],
      'is_new_window' => FALSE,
      'is_external' => FALSE,
      'is_expanded' => FALSE,
      'icon' => 'magnifier',
      'below' => [],
    ];
  }
}

/**
 * Gets the mobile search links from search blocks.
 *
 * @return array<array>
 *   Search links to append to mobile navigation top menu.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_get_mobile_search_links(): array {
  // If we have multiple search blocks, they can all add links to the mobile
  // menu.
  $block_storage = \Drupal::entityTypeManager()->getStorage('block_content');
  $blocks = $block_storage->loadByProperties(['type' => 'civictheme_search']);
  $cacheable_metadata = new CacheableMetadata();
  $search_links = [
    'items' => [],
  ];
  /** @var \Drupal\block_content\Entity\BlockContent $block */
  foreach ($blocks as $block) {
    $cacheable_metadata->addCacheableDependency($block);
    $link_in_mobile_menu = civictheme_get_field_value($block, 'field_c_b_link_in_mobile_menu') ?? FALSE;

    if ($link_in_mobile_menu) {
      $link = civictheme_get_field_value($block, 'field_c_b_link', TRUE);
      if ($link !== NULL) {
        $search_links['items'][] = [
          'url' => $link->getUrl()->toString(),
          'text' => $link->get('title')->getString(),
        ];
      }
    }
  }
  $cacheable_metadata->applyTo($search_links);
  return $search_links;
}
