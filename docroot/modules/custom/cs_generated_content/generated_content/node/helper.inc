<?php

/**
 * @file
 * Node creation helpers.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;

/**
 * Create nodes from variations.
 *
 * @param string $bundle
 *   Node bundle name.
 * @param array $variations
 *   Array of variations to create nodes from.
 *
 * @return array
 *   Array of created nodes.
 */
function _cs_generated_content_create_nodes_from_variations($bundle, array $variations) {
  $nodes = [];

  $helper = CsGeneratedContentHelper::getInstance();

  foreach ($variations as $i => $variation) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = _cs_generated_content_create_node_from_variation($bundle, $variation, $i);

    $variation_info = $helper::variationFormatInfo($variation);
    $node->revision_log = $variation_info;

    $node->save();

    $helper::log(
      'Created "%s" node "%s" [ID: %s] %s',
      $node->bundle(),
      $node->toLink()->toString(),
      $node->id(),
      Link::createFromRoute('Edit', 'entity.node.edit_form', ['node' => $node->id()])->toString()
    );

    $nodes[] = $node;
  }

  return $nodes;
}

/**
 * Create a node object from the variation.
 */
function _cs_generated_content_create_node_from_variation($bundle, $variation, $variation_idx) {
  $node = Node::create([
    'type' => $bundle,
    'title' => 'Node title from variation',
    'status' => $variation['status'],
    'moderation_state' => $variation['status'] ? 'published' : 'draft',
  ]);

  // Process variation using a per-bundle processor, if defined.
  // Processors should do at least 2 checks for every field:
  // - the field in variation was proved,
  // - the Drupal field exists on the entity.
  $processor = sprintf('_cs_generated_content_create_node_%s__fields_from_variation', $bundle);
  if (function_exists($processor)) {
    call_user_func($processor, $node, $variation, $variation_idx);
  }

  return $node;
}

/**
 * Create menu item.
 */
function _cs_generated_content_create_menu_item($menu_name, $title, $uri) {
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
