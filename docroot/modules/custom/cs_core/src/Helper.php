<?php

namespace Drupal\cs_core;

use Drupal\block_content\Entity\BlockContent;
use Drupal\Component\Utility\UrlHelper;
use Drupal\config\StorageReplaceDataWrapper;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageComparer;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\Core\Utility\UpdateException;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\media\Entity\Media;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drush\Drush;
use Drush\Log\LogLevel;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Helper.
 *
 * Helper class for manipulating content.
 *
 * @package Drupal\osp_core
 */
class Helper {

  const FILE_UNCHANGED = 1;

  const FILE_CREATED = 2;

  const FILE_REPLACED = 3;

  /**
   * Get term by name from specified vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine name.
   * @param string $name
   *   Term name.
   *
   * @return \Drupal\taxonomy\Entity\Term|null
   *   Found term or NULL.
   */
  public static function getTermByName($vid, $name) {
    $terms = taxonomy_term_load_multiple_by_name($name, $vid);

    return !empty($terms) ? reset($terms) : NULL;
  }

  /**
   * Get terms at the sepcific depth.
   *
   * @param string $vid
   *   Vocabulary machine name.
   * @param int $depth
   *   Terms depth.
   * @param bool $load_entities
   *   If TRUE, a full entity load will occur on the term objects. Otherwise
   *   they are partial objects queried directly from the {taxonomy_term_data}
   *   table to save execution time and memory consumption when listing large
   *   numbers of terms. Defaults to FALSE.
   *
   * @return \Drupal\taxonomy\Entity\Term[]
   *   Array of terms at depth.
   */
  public static function getTermsAtDepth($vid, $depth, $load_entities = FALSE) {
    $depth = $depth < 0 ? 0 : $depth;

    /** @var \Drupal\taxonomy\Entity\Term [] $tree */
    // Note that we are asking for an item 1 level deeper because this is
    // how loadTree() calculates max depth.
    $tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, 0, $depth + 1, $load_entities);

    foreach ($tree as $k => $leaf) {
      if ($leaf->depth != $depth) {
        unset($tree[$k]);
      }
    }

    return $tree;
  }

  /**
   * Save terms, specified as simplified term tree.
   *
   * @param string $vid
   *   Vocabulary machine name.
   * @param array $tree
   *   Array of tree items, where keys with array values are considered parent
   *   terms.
   * @param bool|int $parent_tid
   *   Internal parameter used for recursive calls.
   *
   * @return array
   *   Array of saved terms, keyed by term id.
   */
  public static function saveTermTree($vid, array $tree, $parent_tid = FALSE) {
    $terms = [];
    $weight = 0;

    foreach ($tree as $parent => $subtree) {
      $term = Term::create([
        'name' => is_array($subtree) ? $parent : $subtree,
        'vid' => $vid,
        'weight' => $weight,
        'parent' => $parent_tid !== FALSE ? $parent_tid : 0,
      ]);

      $term->save();
      $terms[$term->id()] = $term;

      if (is_array($subtree)) {
        $terms += self::saveTermTree($vid, $subtree, $term->id());
      }

      $weight++;
    }

    return $terms;
  }

  /**
   * Clear all terms in vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine name.
   */
  public static function clearVocabulary($vid) {
    $result = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', $vid)
      ->execute();

    $controller = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $entities = $controller->loadMultiple($result);
    $controller->delete($entities);
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
   * Updated menu item link.
   *
   * @param string $menu
   *   The menu name.
   * @param string $existing_link
   *   The existing link to search for.
   * @param string $new_link
   *   The new link to update to.
   */
  public static function updateMenuItemLink($menu, $existing_link, $new_link) {
    $menu_item = self::findMenuItemByLink($menu, $existing_link);

    if (!$menu_item) {
      return FALSE;
    }

    $menu_item->set('link', $new_link);
    $menu_item->save();
  }

  /**
   * Find menu item by link.
   *
   * @param string $menu
   *   The menu name.
   * @param string $link
   *   The link to search for.
   *
   * @return bool|\Drupal\menu_link_content\MenuLinkContentInterface
   *   Found menu item or FALSE.
   */
  public static function findMenuItemByLink($menu, $link) {
    /** @var \Drupal\menu_link_content\MenuLinkContentStorage $storage */
    $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

    $menu_items = $storage->loadByProperties([
      'menu_name' => $menu,
      'link__uri' => $link,
    ]);

    if (empty($menu_items)) {
      return FALSE;
    }

    // Only support the very first found item.
    $menu_item = array_shift($menu_items);

    return $menu_item;
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
   * Recursively flatten previously loaded menu tree.
   */
  public static function flattenMenuTree($tree) {
    $list = [];

    foreach ($tree as $id => $leaf) {
      $list[$id] = $leaf;
      if (!empty($leaf->subtree)) {
        $list = array_merge($list, self::flattenMenuTree($leaf->subtree));
      }
    }

    return $list;
  }

  /**
   * Check if menu item has children.
   *
   * @param \Drupal\menu_link_content\Entity\MenuLinkContent $menu_item
   *   Menu items to check.
   *
   * @return bool|null
   *   TRUE if menu item has children, FALSE otherwise. NULL if the menu item
   *   was not found.
   */
  public static function menuItemHasChildren(MenuLinkContent $menu_item) {
    $menu_tree = \Drupal::menuTree();
    $menu_name = $menu_item->getMenuName();

    $parameters = new MenuTreeParameters();
    $tree = $menu_tree->load($menu_name, $parameters);
    $tree = static::flattenMenuTree($tree);

    foreach ($tree as $leaf) {
      if (
        $leaf->link->getMenuName() === $menu_item->getMenuName() &&
        $leaf->link->getTitle() === $menu_item->getTitle() &&
        $leaf->link->getUrlObject()->toString() === $menu_item->getUrlObject()->toString()
      ) {
        return !empty($leaf->subtree);
      }
    }
    return NULL;
  }

  /**
   * Intersect arrays by column.
   *
   * @param string $column
   *   Column name.
   * @param ...
   *   Variable number of arrays.
   *
   * @return array
   *   Array of intersected values.
   *
   * @throws \Exception
   */
  public static function arrayIntersectColumn($column) {
    $arrays = func_get_args();
    array_shift($arrays);

    if (count($arrays) < 1) {
      throw new \Exception('At least one array argument is required');
    }

    foreach ($arrays as $k => $array) {
      if (!is_array($array)) {
        throw new \Exception(sprintf('Argument %s is not an array', $k + 1));
      }
    }

    $carry = array_shift($arrays);
    foreach ($arrays as $k => $array) {
      $carry_column = self::arrayColumn($carry, $column);
      $array_column = self::arrayColumn($array, $column);
      $column_values = array_intersect($carry_column, $array_column);

      $carry = array_filter($array, function ($item) use ($column, $column_values) {
        $value = self::extractProperty($item, $column);

        return $value && in_array($value, $column_values);
      });
    }

    return $carry;
  }

  /**
   * Portable array_column with support for methods.
   */
  public static function arrayColumn(array $array, $key) {
    if (!is_scalar($key)) {
      throw new \Exception('Specified key is not scalar');
    }

    return array_map(function ($item) use ($key) {
      return self::extractProperty($item, $key);
    }, $array);
  }

  /**
   * Helper to extract property.
   *
   * Note that this helper supports extracting values from simple methods.
   *
   * @param mixed $item
   *   Array or object.
   * @param string $key
   *   Array key or object property or method.
   *
   * @return mixed|null
   *   For arrays - value at specified key.
   *   For objects - value of the specified property or returned value of the
   *   method.
   *
   * @throws \Exception
   *   If key is not scalar.
   *   If item is not an array or an object.
   *   If item is an object, but does not have a property or a method with
   *   specified name.
   *   If item is an array and does not have an element with specified key.
   */
  protected static function extractProperty($item, $key) {
    if (!is_scalar($key)) {
      throw new \Exception('Specified key is not scalar');
    }

    if (!is_object($item) && !is_array($item)) {
      throw new \Exception(sprintf('Item with key "%s" must be an object or an array', $key));
    }

    if (is_object($item)) {
      if (method_exists($item, $key)) {
        return $item->{$key}();
      }
      elseif (property_exists($item, $key)) {
        return $item->{$key};
      }
      throw new \Exception(sprintf('Key "%s" is not a property or a method of an object', $key));
    }
    elseif (is_array($item)) {
      if (isset($item[$key])) {
        return $item[$key];
      }
      throw new \Exception(sprintf('Key "%s" does not exist in array', $key));
    }

    return NULL;
  }

  /**
   * Helper to load media entity by name.
   *
   * @param string $name
   *   The media name.
   * @param string $bundle
   *   (optional) The media bundle name.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\media\Entity\Media
   *   The media instance or NULL.
   */
  public static function loadMediaByName($name, $bundle = NULL) {
    $query = \Drupal::entityQuery('media');
    $query->condition('name', $name);
    if ($bundle) {
      $query->condition('bundle', $bundle);
    }
    $ids = $query->execute();

    if (empty($ids)) {
      return NULL;
    }

    $id = array_shift($ids);

    return Media::load($id);
  }

  /**
   * Create Image media from provided image.
   *
   * @param string $filepath
   *   The full path to the file on the disk.
   * @param bool $alt
   *   (optional) Alternative text for the file.
   * @param string $bundle
   *   (optional) The image media bundle name. Defaults to 'image'.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\media\Entity\Media
   *   Created media object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createMediaImage($filepath, $alt = FALSE, $bundle = 'image') {
    if (!is_readable($filepath)) {
      throw new \Exception(sprintf('Unable to find file "%s"', $filepath));
    }

    $file_name = pathinfo($filepath, PATHINFO_FILENAME);
    $file_ext = pathinfo($filepath, PATHINFO_EXTENSION);
    $file_full_name = $file_name . (!empty($file_ext) ? '.' . $file_ext : '');

    $alt = $alt ? $alt : sprintf('Alt for %s', $file_full_name);

    $uri = 'public://' . $file_full_name;

    $file = file_save_data(file_get_contents($filepath), $uri);

    $media = Media::create([
      'bundle' => $bundle,
      'name' => $file_full_name,
    ]);

    $media->field_m_image->setValue($file->id());
    $media->field_m_image->alt = $alt;
    $media->save();

    return $media;
  }

  /**
   * Helper to create or load a media image by the file name.
   */
  public static function createOrLoadMediaImage($filepath, $alt = FALSE) {
    $file_name = pathinfo($filepath, PATHINFO_FILENAME);
    $file_ext = pathinfo($filepath, PATHINFO_EXTENSION);
    $file_full_name = $file_name . (!empty($file_ext) ? '.' . $file_ext : '');

    $media = Helper::loadMediaByName($file_full_name, 'image');
    if (!$media) {
      $media = Helper::createMediaImage($filepath, $alt);
    }

    if (!$media) {
      throw new \Exception(sprintf('Unable to create or load and image "%s"', $filepath));
    }

    return $media;
  }

  /**
   * Helper to print messages.
   *
   * Prints to stdout if using drush, or drupal_set_message() if the web UI.
   *
   * It is important to note that using \Drupal::messenger() when running Drush
   * commands have side effects where messages are displayed only after the
   * command has finished rather then during the command run.
   *
   * @param string $message
   *   String containing message.
   * @param string $prefix
   *   Prefix to be used for messages when called through CLI.
   *   Defaults to '-- '.
   * @param int $indent
   *   Indent for messages. Defaults to 2.
   */
  public static function log($message, $prefix = '-- ', $indent = 2) {
    if (class_exists('\Drush\Drush')) {
      /** @var \Drush\Log\Logger $logger */
      $logger = Drush::getContainer()->get('logger');
      $logger->log(LogLevel::SUCCESS, str_pad(((string) $prefix) . html_entity_decode($message), $indent, ' ', STR_PAD_LEFT));
    }
    elseif (PHP_SAPI === 'cli') {
      print str_pad(((string) $prefix) . html_entity_decode($message), $indent, ' ', STR_PAD_LEFT) . PHP_EOL;
    }
    else {
      $messenger = \Drupal::messenger();
      if (isset($message)) {
        $messenger->addMessage($message);
      }
    }
  }

  /**
   * Load block_content entity by UUID.
   *
   * @param string $uuid
   *   Block content UUID.
   *
   * @return \Drupal\block_content\Entity\BlockContent|\Drupal\Core\Entity\EntityInterface|null
   *   Loaded block or NULL if block cannot be found.
   */
  public static function loadBlockContentByUuid($uuid) {
    $query = \Drupal::entityQuery('block_content')
      ->condition('uuid', $uuid);
    $ids = $query->execute();

    if (empty($ids)) {
      return NULL;
    }

    $id = array_shift($ids);

    return BlockContent::load($id);
  }

  /**
   * Helper to read configuration from provided locations.
   *
   * Drupal\Core\Site\Settings::get('config_sync_directory')
   * is prepended to the beginning of the locations list to perform lookup in
   * active configuration.
   *
   * @param string $id
   *   Configuration id.
   * @param array $locations
   *   Array of paths to lookup configuration files.
   * @param bool $prioritise_sync
   *   Whether to prioritise the same config in CONFIG_SYNC.
   *
   * @return mixed
   *   Configuration value.
   *
   * @throws \Exception
   *   If configuration file was not found in any specified location.
   */
  public static function readConfig($id, array $locations = [], $prioritise_sync = TRUE) {
    static $storages;

    global $config_directories;

    if (!$prioritise_sync) {
      // CONFIG_SYNC has lower priority.
      array_push($locations, $config_directories[Settings::get('config_sync_directory')]);
    }
    else {
      // CONFIG_SYNC has top priority.
      array_unshift($locations, $config_directories[Settings::get('config_sync_directory')]);
    }

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
   * Helper to ensure that specified configuration is present.
   *
   * Used in install and update hooks to automatically install required
   * configuration from active configuration or, if does not exist, from
   * provided locations (usually, 'config/install' in module's directory).
   *
   * Helpful to avoid cases when configuration import may remove and add back
   * entities, because install/update hook does not use the same config items
   * as already exported.
   *
   * @param string $id
   *   Configuration id.
   * @param array $locations
   *   Array of paths to lookup configuration files.
   */
  public static function ensureConfig($id, array $locations = []) {
    $config_data = self::readConfig($id, $locations);
    \Drupal::service('config.storage')->write($id, $config_data);
  }

  /**
   * Helper to reload default configuration provided by a module.
   *
   * @param string $module
   *   Module name.
   */
  public static function reloadDefaultConfig($module) {
    /** @var \Drupal\Core\Config\ConfigInstaller $config_installer */
    $config_installer = \Drupal::service('config.installer');
    $config_installer->installDefaultConfig('module', $module);
  }

  /**
   * Helper to import a single config item (in a quick and dirty way).
   *
   * @param string $config_name
   *   Config name.
   * @param array $locations
   *   Array of Locations.
   * @param bool $prioritise_sync
   *   Whether to prioritise the same config in CONFIG_SYNC.
   *
   * @throws \Exception
   */
  public static function importSingleConfig($config_name, array $locations = [], $prioritise_sync = TRUE) {
    $config_data = self::readConfig($config_name, $locations, $prioritise_sync);
    unset($config_data['uuid']);
    $config_storage = \Drupal::service('config.storage');
    $event_dispatcher = \Drupal::service('event_dispatcher');
    $config_manager = \Drupal::service('config.manager');
    $lock_persistent = \Drupal::service('lock.persistent');
    $config_typed = \Drupal::service('config.typed');
    $module_handler = \Drupal::service('module_handler');
    $module_installer = \Drupal::service('module_installer');
    $theme_handler = \Drupal::service('theme_handler');
    $string_translation = \Drupal::service('string_translation');
    $extension_list_module = \Drupal::service('extension.list.module');
    \Drupal::config($config_name);

    $source_storage = new StorageReplaceDataWrapper($config_storage);
    $source_storage->replaceData($config_name, $config_data);

    $storage_comparer = new StorageComparer(
      $source_storage,
      $config_storage,
      $config_manager
    );

    $storage_comparer->createChangelist();

    $config_importer = new ConfigImporter(
      $storage_comparer,
      $event_dispatcher,
      $config_manager,
      $lock_persistent,
      $config_typed,
      $module_handler,
      $module_installer,
      $theme_handler,
      $string_translation,
      $extension_list_module
    );

    try {
      $config_importer->import();
      \Drupal::cache('config')->delete($config_name);
    }
    catch (\Exception $exception) {
      foreach ($config_importer->getErrors() as $error) {
        \Drupal::logger('osp')->error($error);
        \Drupal::messenger()->addError($error);
      }
      throw $exception;
    }
  }

  /**
   * Get short file type; image, video, audio, document etc.
   *
   * @param \Drupal\file\Entity\File $file
   *   The file to find the type.
   *
   * @return string
   *   The short file type.
   */
  public static function fileShortType(File $file) {
    return substr($file->getMimeType(), 0, strpos($file->getMimeType(), '/'));
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
   * Find node by alias.
   *
   * @param string $alias
   *   Node alias.
   * @param bool $load_node
   *   (optional) Flag to load node. Defaults to TRUE.
   *
   * @return \Drupal\node\Entity\Node|int|null
   *   Found node if $load_node is TRUE, or found node id, or NULL.
   */
  public static function findNodeByAlias($alias, $load_node = TRUE) {
    $path = \Drupal::service('path.alias_manager')->getPathByAlias($alias);

    try {
      $params = Url::fromUri('internal:' . $path)->getRouteParameters();
    }
    catch (\Exception $exception) {
      return NULL;
    }

    $entity_type = key($params);

    if ($entity_type != 'node') {
      return NULL;
    }

    $nid = $params[$entity_type];

    if (!$load_node) {
      return $nid;
    }

    $node = \Drupal::entityTypeManager()->getStorage($entity_type)->load($nid);

    return $node;
  }

  /**
   * Check if node field has a value.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The entity to get the values from.
   * @param string $field_name
   *   The field name.
   * @param string $value
   *   The value to check.
   * @param string $key
   *   (optional) The value key. Defaults to 'value'.
   *
   * @return bool
   *   TRUE if the value is present, FALSE otherwise.
   */
  public static function nodeFieldHasValue(Node $node, $field_name, $value, $key = 'value') {
    $field_values = $node->{$field_name}->getValue();

    foreach ($field_values as $field_value) {
      if (isset($field_value[$key]) && $field_value[$key] == $value) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Convert alias to source.
   */
  public static function aliasToSource($alias) {
    $path = \Drupal::service('path.alias_manager')->getPathByAlias($alias);
    return $path != $alias ? 'internal:' . $path : NULL;
  }

  /**
   * Download a file from URL.
   */
  public static function downloadFile($url, $dst) {
    $ch = curl_init($url);

    $fp = fopen($dst, 'wb');

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);

    if (curl_exec($ch) === FALSE) {
      $curl_error = curl_error($ch);
      curl_close($ch);
      fclose($fp);
      throw new \Exception(sprintf('Curl error: %s', $curl_error));
    }

    curl_close($ch);
    fclose($fp);
  }

  /**
   * Replaces file.
   */
  public static function replaceFile($existing_file_uri, $new_file_uri, $create_non_existent = TRUE) {
    if (!is_readable($new_file_uri)) {
      throw new \Exception(sprintf('Unable to find replacement file "%s"', $new_file_uri));
    }

    /** @var \Drupal\file\FileInterface[] $files */
    $existing_files = \Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uri' => $existing_file_uri]);

    $basename = \Drupal::service('file_system')->basename($new_file_uri);

    if (!empty($existing_files)) {
      // Replace all existing files.
      foreach ($existing_files as $existing_file) {
        $existing_file->setFileUri($new_file_uri);
        $existing_file->setFilename($basename);
        $existing_file->save();
      }

      return self::FILE_REPLACED;
    }
    elseif ($create_non_existent) {
      $new_file = File::create([
        'filename' => $basename,
        'uri' => $new_file_uri,
      ]);
      $new_file->save();

      return self::FILE_CREATED;
    }

    return self::FILE_UNCHANGED;
  }

  /**
   * Updates Paragraph Link text.
   */
  public static function updateNodeParagraphLinkText($node, $partial_file_name, $new_text) {
    foreach ($node->field_components as $item) {
      if ($item->entity->bundle() != 'content') {
        continue;
      }

      $html = $item->entity->field_body->value;
      $crawler = new Crawler($html);

      $html = $crawler->filter('body')->html();

      $crawler->filter('a')->each(function ($node, $i) use ($partial_file_name, $new_text) {
        $link_node = $node->getNode(0);

        $href = $link_node->getAttribute('href');

        if (strpos($href, $partial_file_name) !== FALSE) {
          $link_node->removeChild($link_node->firstChild);
          $new_text_node = new \DOMText($new_text);
          $link_node->appendChild($new_text_node);
        }
      });

      $updated_html = $crawler->filter('body')->html();

      if ($html != $updated_html) {
        $item->entity->set('field_body', [
          'value' => $updated_html,
          'format' => 'filtered_html',
        ]);
        $item->entity->save();
      }
    }
  }

  /**
   * Create paragraph entity.
   *
   * @param string $bundle
   *   The paragraph bundle name.
   * @param array $fields
   *   Fields to be added to the paragraph.
   *
   * @return \Drupal\Core\Entity\EntityInterface|Paragraph
   *   The paragraph entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createParagraph(string $bundle, array $fields = []) {
    $paragraph = Paragraph::create([
      'type' => $bundle,
    ]);
    foreach ($fields as $field => $value) {
      $paragraph->set($field, $value);
    }
    $paragraph->save();

    return $paragraph;
  }

  /**
   * Create block entity.
   *
   * @param array $block_content
   *   The block to be created.
   *
   * @return \Drupal\Core\Entity\Block
   *   The block entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createBlock(array $block_content = []) {
    $block = BlockContent::create($block_content);
    $block->save();

    return $block;
  }

  /**
   * Create page stub node.
   *
   * @param string $title
   *   The stub node title.
   * @param string $alias
   *   The alias for the stub node (prepended by /stub automatically).
   *
   * @return string
   *   Created stub page alias.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createPageStub(string $title, string $alias) {
    if (!UrlHelper::isExternal($alias)) {
      // Ensure the URL is flat without any extra elements (such as query).
      $alias = '/stub' . Url::fromUserInput($alias)->setOptions([])->toString();
    }

    // Attempt to load an existing node instead of creating a duplicate.
    $node = self::findNodeByAlias($alias);

    if (empty($node)) {
      $node = Node::create([
        'type' => 'page',
        'title' => $title,
        'status' => Node::PUBLISHED,
        'moderation_state' => 'published',
        'path' => [
          'alias' => $alias,
          'pathauto' => FALSE,
        ],
      ]);
      $node->setOwnerId(1);
      $node->save();
    }

    return $alias;
  }

  /**
   * Create tile paragraph entity.
   *
   * @param array $values
   *   An array of tile details.
   *
   * @return \Drupal\Core\Entity\EntityInterface|Paragraph
   *   The tile paragraph entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createTileParagraph(array $values) {
    $defaults = [
      'heading' => NULL,
      'summary' => NULL,
      'image' => NULL,
      'cta' => NULL,
    ];

    $values += $defaults;

    if (empty($values['heading']) || empty($values['summary'])) {
      return NULL;
    }

    $content = [];

    if (!empty($values['heading'])) {
      $content['field_p_t_heading'] = $values['heading'];
    }

    if (!empty($values['summary'])) {
      $content['field_p_t_summary'] = [
        'value' => $values['summary'],
        'format' => 'rich_text',
      ];
    }

    if (!empty($values['cta'])) {
      $content['field_p_t_cta'] = $values['cta'];
    }

    if (!empty($values['image'])) {
      $content['field_p_t_media'] = self::loadMediaByName($values['image'], 'image');
    }

    return self::createParagraph('tile', $content);
  }

  /**
   * Create file entity.
   *
   * @param string $filepath
   *   The filepath for the file.
   *
   * @return bool|\Drupal\file\FileInterface|false
   *   The file entity or FALSE.
   */
  public static function createFileEntity(string $filepath) {
    if (is_scalar($filepath)
      && strpos(strtolower($filepath), 'http') === 0
      && !empty(pathinfo(parse_url($filepath, PHP_URL_PATH), PATHINFO_EXTENSION))
    ) {
      $destination = 'public://' . basename(parse_url($filepath, PHP_URL_PATH));

      if (@file_get_contents($filepath)) {
        return file_save_data(file_get_contents($filepath), $destination, FileSystemInterface::EXISTS_REPLACE);
      }
      else {
        throw new UpdateException(sprintf('Unable to create file entity from %s.', $filepath));
      }
    }

    return FALSE;
  }

  /**
   * Create a media entity.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file to create the media entity.
   * @param string $bundle
   *   The bundle of the media entity.
   *
   * @return bool|\Drupal\media\MediaInterface
   *   The media entity or FALSE.
   */
  public static function createMediaEntity(FileInterface $file, string $bundle) {
    if ($file) {
      $media_helper = \Drupal::getContainer()->get('govcms_media.media_helper');
      $media_entity = $media_helper->createFromInput($file, [$bundle]);

      if ($media_entity) {
        $media_entity->save();
        return $media_entity;
      }
      else {
        throw new UpdateException(sprintf('Unable to create media entity %s.', $file->getFilename()));
      }
    }

    return FALSE;
  }

  /**
   * Attach a body field to the node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to attach the content to. Passed in as a reference.
   * @param string $value
   *   The value of the body field.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function nodeAttachFieldBody(Node &$node, string $value) {
    $body_values = [
      'field_body' => [
        'value' => $value,
        'format' => 'rich_text',
      ],
    ];

    $paragraph = self::createParagraph('content', $body_values);
    $node->field_components->appendItem($paragraph);
  }

  /**
   * Attach tile list to the node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to attach the content to. Passed in as a reference.
   * @param array $component
   *   An array of $component details.
   * @param bool $use_stub
   *   Whether or not stubs should be created as part of this.
   */
  public static function nodeAttachTileList(Node &$node, array $component, bool $use_stub = FALSE) {
    $defaults = [
      'field_p_tl_layout' => 'single_row',
      'field_p_tl_show_tile_as' => 'default',
      'items' => [],
      'field_p_tl_cta' => NULL,
      'field_p_tl_bg_left' => NULL,
      'field_p_tl_bg_right' => NULL,
      'field_p_tl_fill_parent_width' => NULL,
      'field_p_tl_title' => NULL,
      'field_p_tl_industry' => NULL,
    ];

    $component += $defaults;

    if (empty($component['items'])) {
      return;
    }

    foreach ($component['items'] as $tile) {
      if (!empty($tile['cta']['uri']) && !UrlHelper::isExternal($tile['cta']['uri'])) {
        $found_node = self::findNodeByAlias($tile['cta']['uri']);
        if ($found_node) {
          $tile['cta']['uri'] = 'internal:' . $tile['cta']['uri'];
        }
        elseif ($use_stub) {
          $tile['cta']['uri'] = 'internal:' . self::createPageStub($tile['heading'], $tile['cta']['uri']);
        }
        else {
          throw new \Exception(sprintf('Unable to find a non-stubbed node with alias "%s".', $tile['cta']['uri']));
        }
      }

      $tile_paragraph = self::createTileParagraph($tile);
      if ($tile_paragraph) {
        $tile_paragraphs[] = $tile_paragraph;
      }
    }

    if (empty($tile_paragraphs)) {
      return;
    }

    $content = [
      'field_p_tl_items' => $tile_paragraphs,
      'field_p_tl_layout' => $component['field_p_tl_layout'],
      'field_p_tl_show_tile_as' => $component['field_p_tl_show_tile_as'],
    ];

    if (!empty($component['field_p_tl_bg_left'])) {
      $content['field_p_tl_bg_left'] = self::loadMediaByName($component['field_p_tl_bg_left'], 'image');
    }

    if (!empty($component['field_p_tl_bg_right'])) {
      $content['field_p_tl_bg_right'] = self::loadMediaByName($component['field_p_tl_bg_right'], 'image');
    }

    if (!empty($component['field_p_tl_fill_parent_width'])) {
      $content['field_p_tl_fill_parent_width'] = $component['field_p_tl_fill_parent_width'];
    }

    if (!empty($component['field_p_tl_title'])) {
      $content['field_p_tl_title'] = $component['field_p_tl_title'];
    }

    if (!empty($component['field_p_tl_industry'])) {
      $content['field_p_tl_industry'] = self::getTermByName('industries', $component['field_p_tl_industry']);
    }

    if (!empty($component['field_p_tl_cta'])) {
      if (!UrlHelper::isExternal($component['field_p_tl_cta']['uri'])) {
        $component['field_p_tl_cta']['uri'] = 'internal:' . $component['field_p_tl_cta']['uri'];
      }
      $content['field_p_tl_cta'] = $component['field_p_tl_cta'];
    }

    $tile_list_paragraph = self::createParagraph('tile_list', $content);

    $node->field_components->appendItem($tile_list_paragraph);
  }

  /**
   * Attach listing to the node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to attach the listing to. Passed in as a reference.
   * @param array $listing_details
   *   An array of listing details to be created as a listing paragraph.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function nodeAttachListing(Node &$node, array $listing_details) {
    if (!empty($listing_details['field_p_l_read_more']['uri']) && !UrlHelper::isExternal($listing_details['field_p_l_read_more']['uri'])) {
      $listing_details['field_p_l_read_more']['uri'] = 'internal:' . $listing_details['field_p_l_read_more']['uri'];
    }

    if (!empty($listing_details['field_p_l_industry'])) {
      foreach ($listing_details['field_p_l_industry'] as &$industry) {
        $term = self::getTermByName('industries', $industry);
        if (!empty($term)) {
          $industry = $term->id();
        }
      }
    }

    $listing_paragraph = self::createParagraph('listing', $listing_details);
    $node->field_components->appendItem($listing_paragraph);
  }

  /**
   * Attach promo to the node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to attach the promo to. Passed in as a reference.
   * @param array $values
   *   An array of promo details to be created as a promo paragraph.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function nodeAttachPromo(Node &$node, array $values) {
    if (!empty($values['field_p_pr_cta']['uri']) && !UrlHelper::isExternal($values['field_p_pr_cta']['uri'])) {
      $values['field_p_pr_cta']['uri'] = 'internal:' . $values['field_p_pr_cta']['uri'];
    }
    if (!empty($values['field_p_pr_image'])) {
      $values['field_p_pr_image'] = self::loadMediaByName($values['field_p_pr_image'], 'image');
    }

    if (!empty($values['field_p_pr_icon'])) {
      $values['field_p_pr_icon'] = self::loadMediaByName($values['field_p_pr_icon'], 'image');
    }

    if (empty($values['field_p_pr_style'])) {
      $values['field_p_pr_style'] = 'light';
    }

    $promo_paragraph = self::createParagraph('promo', $values);
    $node->field_components->appendItem($promo_paragraph);
  }

  /**
   * Attach block placement to the node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to attach the block to. Passed in as a reference.
   * @param string $block_name
   *   The name of the block to attach.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function nodeAttachBlockPlacement(Node &$node, string $block_name) {
    $block = self::loadBlockContentByName($block_name);
    if (!empty($block)) {
      $block_paragraph = self::createParagraph('block_placement', ['field_bp_block' => $block]);
      $node->field_components->appendItem($block_paragraph);
    }
  }

  /**
   * Load block_content entity ID by name.
   *
   * @param string $block_name
   *   Block content name.
   *
   * @return array|int
   *   Block ID or FALSE if block content cannot be found.
   */
  public static function loadBlockContentByName($block_name) {
    return \Drupal::entityQuery('block_content')
      ->condition('info', $block_name)
      ->execute();
  }

}
