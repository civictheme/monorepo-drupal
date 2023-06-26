<?php

namespace Drupal\civictheme_migrate;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\Url;
use Drupal\file\FileInterface;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\path_alias\AliasManagerInterface;

/**
 * Class LookupManager.
 *
 * Manages entity lookups.
 */
class LookupManager {

  /**
   * Constructor.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AliasManagerInterface $aliasManager,
    protected FileUsageInterface $fileUsage,
    protected StreamWrapperManager $streamWrapperManager
  ) {
  }

  /**
   * Lookup a node by alias.
   *
   * @param string $alias
   *   The alias to lookup.
   * @param bool $reset
   *   Whether to reset the cache.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node if found, otherwise NULL.
   */
  public function lookupNodeByAlias(string $alias, bool $reset = FALSE): ?NodeInterface {
    if (empty($alias)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);

    if (empty($cache[$alias]) || $reset) {
      $cache[$alias] = NULL;

      $updated_alias = $alias;

      try {
        $updated_alias = $this->aliasManager->getPathByAlias($alias);
        $params = Url::fromUri("internal:$updated_alias")->getRouteParameters();
      }
      catch (\Exception $exception) {
        // Ignore.
      }

      if (!empty($updated_alias) && !empty($params)) {
        $entity_type = key($params);
        if ($entity_type == 'node') {
          $nid = $params[$entity_type];
          $cache[$alias] = $this->entityTypeManager->getStorage('node')->load($nid);
        }
      }
    }

    return $cache[$alias];
  }

  /**
   * Lookup a media entity by file name.
   *
   * @param string $filename
   *   The filename to lookup by.
   * @param bool $reset
   *   Whether to reset the cache.
   *
   * @return \Drupal\media\MediaInterface|null
   *   The media entity if found, otherwise NULL.
   */
  public function lookupMediaByFileName(string $filename, bool $reset = FALSE): ?MediaInterface {
    if (empty($filename)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (empty($cache[$filename]) || $reset) {
      $cache[$filename] = NULL;
      $file = $this->lookupFileByFilename($filename, $reset);
      if ($file) {
        $usage = $this->fileUsage->listUsage($file);
        if (!empty($usage['file']['media'])) {
          $mid = key($usage['file']['media']);
          $cache[$filename] = $this->entityTypeManager->getStorage('media')->load($mid);
        }
      }
    }

    return $cache[$filename];
  }

  /**
   * Lookup a file entity by the file name.
   *
   * @param string $filename
   *   The filename to lookup.
   * @param bool $reset
   *   Whether to reset the cache.
   *
   * @return \Drupal\file\FileInterface|null
   *   The file entity if found, otherwise NULL.
   */
  public function lookupFileByFilename(string $filename, $reset = FALSE): ?FileInterface {
    if (empty($filename)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (empty($cache[$filename]) || $reset) {
      $cache[$filename] = NULL;

      $fids = $this->entityTypeManager->getStorage('file')->getQuery()
        ->accessCheck(FALSE)
        ->condition('filename', $filename)
        ->range(0, 1)
        ->execute();

      if ($fids) {
        $fid = reset($fids);
        $cache[$filename] = $this->entityTypeManager->getStorage('file')->load($fid);
      }
    }

    return $cache[$filename];
  }

}
