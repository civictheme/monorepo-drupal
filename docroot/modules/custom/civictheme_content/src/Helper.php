<?php

namespace Drupal\civictheme_content;

use Drupal\Core\Url;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;
use Drush\Drush;
use Psr\Log\LogLevel;

/**
 * Class Helper.
 *
 * Helper class for manipulating content.
 *
 * @package Drupal\civictheme_content
 */
class Helper {

  /**
   * Helper to print a log message to stdout.
   *
   * It is important to note that using \Drupal::messenger() when running Drush
   * commands have side effects where messages are displayed only after the
   * command has finished rather than during the command run.
   *
   * @param string $message
   *   String containing message.
   */
  public static function log($message) {
    if (class_exists('\Drush\Drush')) {
      Drush::getContainer()->get('logger')->log(LogLevel::INFO, strip_tags(html_entity_decode($message)));
      return;
    }
    elseif (PHP_SAPI === 'cli') {
      print strip_tags(html_entity_decode($message)) . PHP_EOL;
      return;
    }
    \Drupal::messenger()->addMessage($message);
  }

  /**
   * Import links from the provided tree.
   *
   * @code
   * $tree = [
   *   'Item1' => [
   *     'link' => '/path-to-item1',
   *     'children' => [
   *       'Subitem 1' => '/path-to-subitem1',
   *       'Subitem 2' => '/path-to-subitem2',
   *     ],
   *   'Item2' => '/path-to-item2',
   * ];
   * Menu::import('main-menu', $tree);
   * @endcode
   *
   * @param string $menu_name
   *   String machine menu name.
   * @param array $tree
   *   Array of links with keys as titles and values as paths or full link
   *   item array definitions. 'children' key is used to specify children menu
   *   levels.
   * @param \Drupal\menu_link_content\Entity\MenuLinkContent $parent_menu_link
   *   Internal. Parent menu link item.
   *
   * @return array
   *   Array of created mlids.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function saveMenuTree($menu_name, array $tree, MenuLinkContent $parent_menu_link = NULL) {
    $created_mlids = [];
    $weight = 0;
    foreach ($tree as $title => $leaf) {
      $leaf = is_array($leaf) ? $leaf : ['link' => $leaf];

      if (!isset($leaf['link'])) {
        throw new \InvalidArgumentException('Menu item does not contain "link" element');
      }

      if (is_array($leaf['link']) && !isset($leaf['link']['uri'])) {
        throw new \InvalidArgumentException('Menu item contains "link" element which does not contain "uri" value');
      }

      if (!is_array($leaf['link'])) {
        $leaf['link'] = ['uri' => $leaf['link']];
      }

      // Try to convert scalar link to Drupal Url object.
      if (is_string($leaf['link']['uri'])) {
        $leaf['link']['uri'] = Url::fromUserInput($leaf['link']['uri'])->toUriString();
      }

      $leaf_defaults = [
        'menu_name' => $menu_name,
        'title' => $title,
        'weight' => $weight,
      ];
      if ($parent_menu_link) {
        $leaf_defaults['parent'] = 'menu_link_content:' . $parent_menu_link->uuid();
      }

      $leaf += $leaf_defaults;

      $children = $leaf['children'] ?? [];
      unset($leaf['children']);
      if ($children) {
        $leaf += ['expanded' => TRUE];
      }

      $menu_link = MenuLinkContent::create($leaf);
      $menu_link->save();
      $mlid = $menu_link->id();
      if (!$mlid) {
        continue;
      }
      $created_mlids[] = $mlid;
      $weight++;
      if ($children) {
        $created_mlids = array_merge($created_mlids, self::saveMenuTree($menu_name, $children, $menu_link));
      }
    }

    return $created_mlids;
  }

  /**
   * Find menu item by link.
   *
   * @param string $menu
   *   The menu name.
   * @param string $title
   *   The title to search for.
   * @param int $idx
   *   The index of the result to return. Defaults to NULL. If specified, but
   *   an item with this index does not exist - FALSE is returned. If not
   *   specified - a first item from the match is returned.
   *
   * @return bool|\Drupal\menu_link_content\MenuLinkContentInterface
   *   Found menu item or FALSE.
   */
  public static function findMenuItemByTitle($menu, $title, $idx = NULL) {
    /** @var \Drupal\menu_link_content\MenuLinkContentStorage $storage */
    $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

    $menu_items = $storage->loadByProperties([
      'menu_name' => $menu,
      'title' => $title,
    ]);

    if (empty($menu_items)) {
      return FALSE;
    }

    if (!is_null($idx)) {
      $menu_item = $menu_items[$idx] ?? FALSE;
    }
    else {
      // Return the first item.
      $menu_item = array_shift($menu_items);
    }

    return $menu_item;
  }

  /**
   * Load node by title.
   *
   * @param string $title
   *   Title to search for.
   * @param string $type
   *   Optional bundle name to limit the search. Defaults to NULL.
   *
   * @return \Drupal\node\Entity\Node
   *   Found node object or NULL if the node was not found.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function loadNodeByTitle($title, $type = NULL) {
    $query = \Drupal::entityQuery('node');
    $query->condition('title', $title);
    if ($type) {
      $query->condition('type', $type);
    }
    $ids = $query->execute();

    if (empty($ids)) {
      return NULL;
    }

    $id = array_shift($ids);

    return Node::load($id);
  }

  /**
   * Set the site homepage from node.
   *
   * @param string $title
   *   Node title to set as a homepage.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  public static function setHomepageFromNode($title) {
    $node = static::loadNodeByTitle($title, 'civictheme_page');

    if (!$node) {
      throw new \Exception('Unable to find homepage node.');
    }

    $config = \Drupal::service('config.factory')->getEditable('system.site');
    $config->set('page.front', '/node/' . $node->id())->save();
  }

}
