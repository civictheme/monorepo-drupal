<?php

namespace Drupal\civictheme_content;

use Drupal\Component\Serialization\Json;
use Drupal\config\StorageReplaceDataWrapper;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageComparer;
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
   * Clear all items in the menu.
   *
   * @param string $menu_name
   *   String machine menu name.
   */
  public static function clearMenu($menu_name) {
    /** @var \Drupal\menu_link_content\MenuLinkContentStorage $storage */
    $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

    $menu_items = $storage->loadByProperties([
      'menu_name' => $menu_name,
    ]);

    foreach ($menu_items as $menu_item) {
      $menu_item->delete();
    }
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
    $query = \Drupal::entityQuery('node')->accessCheck(FALSE);
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

  /**
   * Check if a theme is a sub-theme of the specified theme.
   *
   * @param string $theme
   *   Theme name to check.
   * @param string $parent_theme
   *   Parent theme name.
   *
   * @return bool
   *   TRUE if a theme is a sub-theme of the specified parent theme,
   *   FALSE otherwise.
   */
  public static function themeIsSubtheme($theme, $parent_theme) {
    $current = \Drupal::service('theme.initialization')->getActiveThemeByName($theme);

    return array_key_exists($parent_theme, $current->getBaseThemeExtensions());
  }

  /**
   * Discover and import all configs within specified locations.
   *
   * @param string|array $locations
   *   A single or an array of locations to discover configs.
   * @param array $tokens
   *   Array of tokens to replace within configs while they are importing.
   */
  public static function importConfigs($locations = [], array $tokens = []) {
    $locations = is_array($locations) ? $locations : [$locations];

    foreach ($locations as $location) {
      foreach (glob($location . '/*') as $file) {
        $src = basename($file, '.yml');
        $dst = self::replaceTokens($src, $tokens);
        self::importSingleConfig($src, $locations, $dst, $tokens);
      }
    }
  }

  /**
   * Import a single config item.
   *
   * Can also be used to import into config with a different name.
   *
   * @param string $src_name
   *   Source config name to import.
   * @param string|array $locations
   *   Location or an array of locations to search for configuration files.
   * @param string $dst_name
   *   Destination config name to import. If not provided - defaults
   *   to $src_name.
   * @param array $tokens
   *   Optional array of tokens to replace while importing configuration.
   */
  public static function importSingleConfig($src_name, $locations, $dst_name = NULL, array $tokens = []) {
    $dst_name = $dst_name ?? $src_name;

    $locations = is_array($locations) ? $locations : [$locations];

    $config_data = self::readConfig($src_name, $locations);
    $config_data = self::replaceTokens($config_data, $tokens);

    unset($config_data['uuid']);

    $config_storage = \Drupal::service('config.storage');

    $source_storage = new StorageReplaceDataWrapper($config_storage);
    $source_storage->replaceData($dst_name, $config_data);

    $storage_comparer = new StorageComparer($source_storage, $config_storage);
    $storage_comparer->createChangelist();

    $config_importer = new ConfigImporter(
      $storage_comparer,
      \Drupal::service('event_dispatcher'),
      \Drupal::service('config.manager'),
      \Drupal::service('lock.persistent'),
      \Drupal::service('config.typed'),
      \Drupal::service('module_handler'),
      \Drupal::service('module_installer'),
      \Drupal::service('theme_handler'),
      \Drupal::service('string_translation'),
      \Drupal::service('extension.list.module')
    );

    try {
      $config_importer->import();
      \Drupal::cache('config')->delete($dst_name);
    }
    catch (\Exception $exception) {
      foreach ($config_importer->getErrors() as $error) {
        \Drupal::logger('helper')->error($error);
        \Drupal::messenger()->addError($error);
      }
      throw $exception;
    }
  }

  /**
   * Read configuration from provided locations.
   *
   * @param string $id
   *   Configuration id.
   * @param array $locations
   *   Array of paths to lookup configuration files.
   *
   * @return mixed
   *   Configuration value.
   *
   * @throws \Exception
   *   If configuration file was not found in any specified location.
   */
  public static function readConfig($id, array $locations) {
    static $storages;

    foreach ($locations as $path) {
      if (file_exists($path . DIRECTORY_SEPARATOR . $id . '.yml')) {
        $storages[$path] = new FileStorage($path);
        break;
      }
    }

    if (!isset($storages[$path])) {
      throw new \Exception('Configuration does not exist in any provided locations');
    }

    return $storages[$path]->read($id);
  }

  /**
   * Replace tokens within data keys and values.
   *
   * @param mixed $data
   *   Date to replace tokens in.
   * @param array $tokens
   *   Array of tokens to replace the data. Tokens with values of FALSE will be
   *   preserved.
   *
   * @return mixed
   *   Data with replaced tokens.
   */
  protected static function replaceTokens($data, array $tokens = []) {
    $replace = array_filter($tokens);
    // Retrieve tokens that should be preserved.
    $preserve = array_diff_key($tokens, $replace);

    $preserve_in = [];
    $preserve_out = [];
    foreach (array_keys($preserve) as $k => $name) {
      $preserve_in[$name] = 'PRESERVE_BEGIN' . $k . 'PRESERVE_END';
      $preserve_out['PRESERVE_BEGIN' . $k . 'PRESERVE_END'] = $name;
    }

    $encoded = Json::encode($data);

    $encoded = strtr($encoded, $preserve_in);
    $encoded = strtr($encoded, $replace);
    $encoded = strtr($encoded, $preserve_out);

    return Json::decode($encoded);
  }

}
