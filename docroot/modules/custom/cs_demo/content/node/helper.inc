<?php

/**
 * @file
 * Node creation helpers.
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;

/**
 * Create a node object from the variation.
 */
function _cs_demo_create_node_from_variation($bundle, $variation) {
  $node = Node::create([
    'type' => $bundle,
    'title' => 'Node title from variation',
    'status' => $variation['status'],
    'moderation_state' => $variation['status'] ? 'published' : 'draft',
  ]);

  return $node;
}

/**
 * Create menu item.
 */
function _cs_demo_create_menu_item($menu_name, $title, $uri) {
  $parent_title = 'Demo content';
  $parent = Drupal::entityTypeManager()->getStorage('menu_link_content')
    ->loadByProperties([
      'menu_name' => $menu_name,
      'title' => $parent_title,
    ]);
  $parent = is_array($parent) ? reset($parent) : $parent;
  if (empty($parent)) {
    $parent = MenuLinkContent::create([
      'title' => $parent_title,
      'link' => ['uri' => 'route:<front>'],
      'menu_name' => $menu_name,
      'enabled' => 0,
      'weight' => 1000,
    ])->save();
  }

  if (is_object($parent)) {
    MenuLinkContent::create([
      'title' => $title,
      'link' => ['uri' => $uri],
      'menu_name' => $menu_name,
      'weight' => 0,
      'parent' => 'menu_link_content:' . $parent->uuid(),
    ])->save();
  }
}
