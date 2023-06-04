<?php

namespace Drupal\civictheme_migrate\Utils;

use Drupal\node\NodeInterface;
use Drupal\Core\Url;

/**
 * Node helper class.
 *
 * @package Drupal\civictheme_migrate\Utils
 */
class NodeHelper {

  /**
   * Lookup an existing Node entity using a Alias URL.
   *
   * @param string $alias
   *   The Alias URL.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity.
   */
  public static function lookupNodeFromAlias(string $alias) : ?NodeInterface {
    if (empty($alias)) {
      return NULL;
    }
    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$alias])) {
      return $cache[$alias];
    }
    try {
      $alias = \Drupal::service('path_alias.manager')->getPathByAlias($alias);
      $params = Url::fromUri("internal:" . $alias)->getRouteParameters();
    }
    catch (\Exception $exception) {
      return NULL;
    }
    $entity_type = key($params);
    if ($entity_type != 'node') {
      return NULL;
    }
    $nid = $params[$entity_type];
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var \Drupal\node\NodeInterface $node */
    $node = $storage->load($nid);
    if ($node) {
      $cache[$alias] = $node;
      return $node;
    }
    return NULL;
  }

  /**
   * Lookup the UUID of a node entity using a Alias URL.
   *
   * @param string $alias
   *   The Alias URL.
   *
   * @return string|null
   *   The node entity UUID.
   */
  public static function lookupNodeUuidFromAlias(string $alias) : ?string {
    $node = static::lookupNodeFromAlias($alias);
    return $node ? $node->uuid() : NULL;
  }

  /**
   * Lookup the ID of a node entity using a Alias URL.
   *
   * @param string $alias
   *   The Alias URL.
   *
   * @return string|null
   *   The node entity ID.
   */
  public static function lookupNodeIdFromAlias(string $alias) : ?string {
    $node = static::lookupNodeFromAlias($alias);
    return $node ? $node->id() : NULL;
  }

  /**
   * Lookup the Uri of a node entity using a Alias URL.
   *
   * @param string $alias
   *   The Alias URL.
   *
   * @return string|null
   *   The node entity Uri.
   */
  public static function lookupNodeUriFromAlias(string $alias) : ?string {
    $node = static::lookupNodeFromAlias($alias);
    return $node ? 'entity:node/' . $node->id() : NULL;
  }

  /**
   * Lookup the path of a node entity using a Alias URL.
   *
   * @param string $alias
   *   The Alias URL.
   *
   * @return string|null
   *   The node entity path.
   */
  public static function lookupNodePathFromAlias(string $alias) : ?string {
    $node = static::lookupNodeFromAlias($alias);
    return $node ? '/node/' . $node->id() : NULL;
  }

  /**
   * Lookup the Uri of a node entity using the Title and Node Type.
   *
   * @param string $title
   *   The Title.
   * @param string $type
   *   The node Type.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity.
   */
  public static function lookupNodeFromTitleAndType(string $title, string $type) : ?NodeInterface {
    if (empty($title) || empty($type)) {
      return NULL;
    }
    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$title][$type])) {
      return $cache[$title][$type];
    }
    $results = \Drupal::entityQuery('node')
      ->condition('title', $title)
      ->condition('type', $type)
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($results) {
      $nid = reset($results);
      $storage = \Drupal::entityTypeManager()->getStorage('node');
      /** @var \Drupal\node\NodeInterface $node */
      $node = $storage->load($nid);
      if ($node) {
        $cache[$title][$type] = $node;
        return $node;
      }
    }
    return NULL;
  }

  /**
   * Lookup the Uri of a node entity using the SiteCore ID and Url.
   *
   * @param string $title
   *   The SiteCore ID.
   * @param string $type
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity ID.
   */
  public static function lookupNodeUriTitleAndType(string $title, string $type) : ?string {
    $node = static::lookupNodeFromTitleAndType($title, $type);
    return $node ? 'entity:node/' . $node->id() : NULL;
  }
}
