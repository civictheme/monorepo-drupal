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
   * Lookup the UUID of a node entity using a The SiteCore URL.
   *
   * @param string $sitecore_url
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity UUID.
   */
  public static function lookupNodeUuidFromSiteCoreUrl(string $sitecore_url) : ?string {
    $node = static::lookupNodeFromSiteCoreUrl($sitecore_url);
    return $node ? $node->uuid() : NULL;
  }

  /**
   * Lookup the ID of a node entity using a The SiteCore URL.
   *
   * @param string $sitecore_url
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity ID.
   */
  public static function lookupNodeIdFromSiteCoreUrl(string $sitecore_url) : ?string {
    $node = static::lookupNodeFromSiteCoreUrl($sitecore_url);
    return $node ? $node->id() : NULL;
  }

  /**
   * Lookup the Uri of a node entity using a The SiteCore URL.
   *
   * @param string $sitecore_url
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity Uri.
   */
  public static function lookupNodeUriFromSiteCoreUrl(string $sitecore_url) : ?string {
    $node = static::lookupNodeFromSiteCoreUrl($sitecore_url);
    return $node ? 'entity:node/' . $node->id() : NULL;
  }

  /**
   * Lookup the path of a node entity using a The SiteCore URL.
   *
   * @param string $sitecore_url
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity path.
   */
  public static function lookupNodePathFromSiteCoreUrl(string $sitecore_url) : ?string {
    $node = static::lookupNodeFromSiteCoreUrl($sitecore_url);
    return $node ? '/node/' . $node->id() : NULL;
  }

  /**
   * Lookup an existing Node entity using a SiteCore ID.
   *
   * @param string $sitecore_id
   *   The SiteCore ID.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity.
   */
  public static function lookupNodeFromSiteCoreId(string $sitecore_id) : ?NodeInterface {
    if (empty($sitecore_id)) {
      return NULL;
    }
    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$sitecore_id])) {
      return $cache[$sitecore_id];
    }
    $results = \Drupal::entityQuery('node')
      ->condition('field_sitecore_id', $sitecore_id)
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($results) {
      $nid = reset($results);
      $storage = \Drupal::entityTypeManager()->getStorage('node');
      /** @var \Drupal\node\NodeInterface $node */
      $node = $storage->load($nid);
      if ($node) {
        $cache[$sitecore_id] = $node;
        return $node;
      }
    }
    return NULL;
  }

  /**
   * Lookup the Uri of a node entity using a The SiteCore ID.
   *
   * @param string $sitecore_id
   *   The The SiteCore ID.
   *
   * @return string|null
   *   The node entity Uri.
   */
  public static function lookupNodeUriFromSiteCoreId(string $sitecore_id) : ?string {
    $node = static::lookupNodeFromSiteCoreId($sitecore_id);
    return $node ? 'entity:node/' . $node->id() : NULL;
  }

  /**
   * Lookup an existing Node entity using the SiteCore ID and Url.
   *
   * @param string $sitecore_id
   *   The SiteCore ID.
   * @param string $sitecore_url
   *   The SiteCore Url.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity.
   */
  public static function lookupNodeFromSiteCoreIdAndUrl(string $sitecore_id, string $sitecore_url) : ?NodeInterface {
    if (empty($sitecore_id) || empty($sitecore_url)) {
      return NULL;
    }
    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$sitecore_id][$sitecore_url])) {
      return $cache[$sitecore_id][$sitecore_url];
    }
    $results = \Drupal::entityQuery('node')
      ->condition('field_sitecore_id', $sitecore_id)
      ->condition('field_sitecore_url', $sitecore_url)
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($results) {
      $nid = reset($results);
      $storage = \Drupal::entityTypeManager()->getStorage('node');
      /** @var \Drupal\node\NodeInterface $node */
      $node = $storage->load($nid);
      if ($node) {
        $cache[$sitecore_id][$sitecore_url] = $node;
        return $node;
      }
    }
    return NULL;
  }

  /**
   * Lookup the ID of a node entity using the SiteCore ID and Url.
   *
   * @param string $sitecore_id
   *   The SiteCore ID.
   * @param string $sitecore_url
   *   The The SiteCore URL.
   *
   * @return string|null
   *   The node entity ID.
   */
  public static function lookupNodeIdFromSiteCoreIdAndUrl(string $sitecore_id, string $sitecore_url) : ?string {
    $node = static::lookupNodeFromSiteCoreIdAndUrl($sitecore_id, $sitecore_url);
    return $node ? $node->id() : NULL;
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

  /**
   * Lookup an existing Node entity using the old Url.
   *
   * @param string $field_old_url
   *   The old url.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity.
   */
  public static function lookupNodeFromOldUrl(string $field_old_url) : ?NodeInterface {
    if (empty($field_old_url)) {
      return NULL;
    }
    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$field_old_url])) {
      return $cache[$field_old_url];
    }
    $results = \Drupal::entityQuery('node')
      ->condition('field_old_url', '%' . \Drupal::database()->escapeLike($field_old_url), 'LIKE')
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($results) {
      $nid = reset($results);
      $storage = \Drupal::entityTypeManager()->getStorage('node');
      /** @var \Drupal\node\NodeInterface $node */
      $node = $storage->load($nid);
      if ($node) {
        $cache[$field_old_url] = $node;
        return $node;
      }
    }
    return NULL;
  }

  /**
   * Lookup the Uri of a node entity using the old Url.
   *
   * @param string $field_old_url
   *   The old Url.
   *
   * @return string|null
   *   The node entity Uri.
   */
  public static function lookupNodeUriFromOldUrl(string $field_old_url) : ?string {
    $node = static::lookupNodeFromOldUrl($field_old_url);
    return $node ? 'entity:node/' . $node->id() : NULL;
  }

}
