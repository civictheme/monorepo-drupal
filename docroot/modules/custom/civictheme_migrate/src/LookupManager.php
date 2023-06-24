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
   * Lookup a media entity by file URI.
   *
   * @param string $uri
   *   The URI to lookup.
   * @param bool $reset
   *   Whether to reset the cache.
   *
   * @return \Drupal\media\MediaInterface|null
   *   The media entity if found, otherwise NULL.
   */
  public function lookupMediaByFileUri(string $uri, bool $reset = FALSE): ?MediaInterface {
    if (empty($uri)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (empty($cache[$uri]) || $reset) {
      $cache[$uri] = NULL;
      $file = $this->lookupFileByUri($uri, $reset);
      if ($file) {
        $usage = $this->fileUsage->listUsage($file);
        if (!empty($usage['file']['media'])) {
          $mid = key($usage['file']['media']);
          $cache[$uri] = $this->entityTypeManager->getStorage('media')->load($mid);
        }
      }
    }

    return $cache[$uri];
  }

  /**
   * Lookup a file entity by URI.
   *
   * @param string $uri
   *   The URI to lookup.
   * @param bool $reset
   *   Whether to reset the cache.
   *
   * @return \Drupal\file\FileInterface|null
   *   The file entity if found, otherwise NULL.
   */
  public function lookupFileByUri(string $uri, $reset = FALSE): ?FileInterface {
    if (empty($uri)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (empty($cache[$uri]) || $reset) {
      $cache[$uri] = NULL;

      $updated_uri = $uri;

      // Normalize the URI if it's not a valid scheme.
      $parts = explode('://', $uri);
      $scheme = $parts[0] ?? '';
      if (!$this->streamWrapperManager->isValidScheme($scheme)) {
        $updated_uri = str_replace($this->streamWrapperManager->getViaUri('public://')->basePath() . '/', 'public://', ltrim($uri, '/'));
      }

      $fids = $this->entityTypeManager->getStorage('file')->getQuery()
        ->accessCheck(FALSE)
        ->condition('uri', $updated_uri)
        ->range(0, 1)
        ->execute();

      if ($fids) {
        $fid = reset($fids);
        $cache[$uri] = $this->entityTypeManager->getStorage('file')->load($fid);
      }
    }

    return $cache[$uri];
  }

}
