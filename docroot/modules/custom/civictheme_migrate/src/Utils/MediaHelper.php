<?php

namespace Drupal\civictheme_migrate\Utils;

use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\media\Entity\Media;
use Drupal\media\Entity\MediaType;
use Drupal\media\MediaInterface;
use Drupal\migrate\MigrateExecutable;

/**
 * Media Support helper class.
 *
 * @package Drupal\civictheme_migrate\Utils
 */
class MediaHelper {

  const FILE_DESTINATION = 'public://migrated/';

  /**
   * Lookup an existing media entity using URL.
   *
   * @param string $file_url
   *   The URL.
   * @param array $context
   *   The migration context.
   * @param bool $create
   *   Whether to create a new media entity if not found.
   *
   * @return \Drupal\media\MediaInterface|null
   *   The media entity.
   */
  public static function lookupMediaFromUrl(string $file_url, array &$context, bool $create = FALSE) : ?MediaInterface {
    if (empty($file_url)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$file_url])) {
      return $cache[$file_url];
    }

    // Lookup existing file.
    $file = static::lookupFileFromUri($file_url);
    if ($file) {
      $results = \Drupal::service('file.usage')->listUsage($file);
      if (!empty($results['file']['media'])) {
        $mid = key($results['file']['media']);
        $storage = \Drupal::entityTypeManager()->getStorage('media');
        /** @var \Drupal\media\MediaInterface $media */
        $media = $storage->load($mid);
        if ($media) {
          $cache[$file_url] = $media;
          return $media;
        }
      }
    }

    // No media found, attempt to download from source.
    if ($create) {
      $media = static::downloadMediaFromUrl($file_url, $context);
      if ($media) {
        $cache[$file_url] = $media;
        return $media;
      }
    }

    return NULL;
  }

  /**
   * Lookup the ID of a media entity using a SiteCore URL.
   *
   * @param string $file_url
   *   The Sitecore URL.
   * @param array $context
   *   The migration context.
   * @param bool $create
   *   Whether to create a new media entity if not found.
   *
   * @return string|null
   *   The media entity ID.
   */
  public static function lookupMediaIdFromUrl(string $file_url, array &$context, bool $create = FALSE) : ?string {
    $media = static::lookupMediaFromUrl($file_url, $context, $create);
    return $media ? $media->id() : NULL;
  }

  /**
   * Lookup the UUID of a media entity using a SiteCore URL.
   *
   * @param string $file_url
   *   The Sitecore URL.
   * @param array $context
   *   The migration context.
   * @param bool $create
   *   Whether to create a new media entity if not found.
   *
   * @return string|null
   *   The media entity UUID.
   */
  public static function lookupMediaUuidFromUrl(string $file_url, array &$context, bool $create = FALSE) : ?string {
    $media = static::lookupMediaFromUrl($file_url, $context, $create);
    return $media ? $media->uuid() : NULL;
  }

  /**
   * Return the HTML of an embedded media entity.
   *
   * @param string $uuid
   *   The UUID of the media.
   * @param string|null $alt
   *   The alt text for the image.
   * @param string|null $title
   *   The title of the image.
   *
   * @return string
   *   The embedded code.
   */
  public static function getEmbeddedMediaCode(string $uuid, string $alt = '', string $title = '') : string {
    $code = '<drupal-media data-entity-embed-display="view_mode:media.embedded" data-entity-type="media" data-entity-uuid="' . $uuid . '" data-langcode="en"';
    if ($alt) {
      $code .= ' alt="' . $alt . '"';
    }
    if ($title) {
      $code .= ' title="' . $title . '"';
    }
    $code .= '></drupal-media>';

    return $code;
  }

  /**
   * Attempt to download a media item from SiteCore URL.
   *
   * @param string $file_url
   *   The SiteCore URL.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\media\MediaInterface|null
   *   The media entity.
   */
  public static function downloadMediaFromUrl(string $file_url, array &$context) : ?MediaInterface {
    $base_url = !empty($context['base_url']) ? $context['base_url'] : '';
    $current_path = '/';
    $file_destination = static::FILE_DESTINATION;
    $source_uri = trim($file_url);
    // Prepend with current_path if this is a relative URL.
    if (strpos($source_uri, '/') !== 0) {
      $source_uri = $current_path . $source_uri;
    }

    // Strip off the query string.
    $destination_uri = AliasHelper::extractAliasPathFromUrl($source_uri);
    if ($destination_uri) {
      $destination_uri = $file_destination . $destination_uri;

      // Lookup existing file.
      $file = static::lookupFileFromUri($destination_uri);
      if (!$file && !empty($base_url)) {
        // Attempt to download the file.
        $source = $base_url . $source_uri;
        $file = static::downloadRemoteFile($source, $destination_uri, $context);
      }

      if ($file) {
        $media = static::createMediaFromFile($file, $context);

        if ($media) {
          return $media;
        }
      }
    }

    return NULL;
  }

  /**
   * Lookup an existing file from URI.
   *
   * @param string $uri
   *   The URL.
   *
   * @return \Drupal\file\FileInterface|null
   *   The found file entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function lookupFileFromUri(string $uri) : ?FileInterface {
    if (empty($uri)) {
      return NULL;
    }

    $cache = &drupal_static(__METHOD__, []);
    if (!empty($cache[$uri])) {
      return $cache[$uri];
    }

    $scheme_uri = 'public://';
    if ($wrapper = \Drupal::service('stream_wrapper_manager')->getViaUri($scheme_uri)) {
      $public_path = $wrapper->basePath();
      $uri = $scheme_uri . ltrim($uri, $public_path);
    }

    $results = \Drupal::entityQuery('file')
      ->condition('uri', $uri)
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();

    if ($results) {
      $fid = reset($results);
      $storage = \Drupal::entityTypeManager()->getStorage('file');
      /** @var \Drupal\file\FileInterface $file */
      $file = $storage->load($fid);
      if ($file) {
        $cache[$uri] = $file;
        return $file;
      }
    }

    return NULL;
  }

  /**
   * Download a remote file.
   *
   * @param string $source
   *   The source URL.
   * @param string $destination
   *   The file URI.
   * @param array $context
   *   The migration context.
   *
   * @return \Drupal\file\FileInterface|null
   *   The file entity.
   */
  public static function downloadRemoteFile(string $source, string $destination, array &$context) : ?FileInterface {
    $migration = $context['migration'] ?? NULL;

    /** @var \Drupal\Core\File\FileSystemInterface $fs */
    $fs = \Drupal::service('file_system');
    $directory = $fs->dirname($destination);
    if (!$fs->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS)) {
      if ($migration instanceof MigrateExecutable) {
        $migration->saveMessage(t('Cannot write to @directory (@source)', [
          '@directory' => $directory,
          '@source' => $source,
        ]));
      }
      return NULL;
    }

    $destination_stream = @fopen($destination, 'wb');
    if (!$destination_stream) {
      if ($migration instanceof MigrateExecutable) {
        $migration->saveMessage(t('Cannot open file stream for @destination (@source)', [
          '@destination' => $destination,
          '@source' => $source,
        ]));
      }
      return NULL;
    }

    try {
      // Make the request. Guzzle throws an exception for anything but 200.
      \Drupal::httpClient()->get($source, [
        'sink' => $destination_stream,
      ]);

      if (@file_exists($destination)) {
        // Create a file entity.
        $file = File::create([
          'uri' => $destination,
          'uid' => 1,
          'status' => FILE_STATUS_PERMANENT,
        ]);
        $file->save();

        return $file;
      }
    }
    catch (\Exception $e) {
      if ($migration instanceof MigrateExecutable) {
        $migration->saveMessage(t('@exception (@source)', [
          '@exception' => $e->getMessage(),
          '@source' => $source,
        ]));
      }
      return NULL;
    }

    return NULL;
  }

  /**
   * Create a media entity from a file entity.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity.
   * @param array $context
   *   The migration context.
   * @param array $media_data
   *   Extra data for the media.
   *
   * @return \Drupal\media\MediaInterface|null
   *   The media.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createMediaFromFile(FileInterface $file, array &$context, array $media_data = []) : ?MediaInterface {
    $migration = $context['migration'] ?? NULL;
    $cache = &drupal_static(__METHOD__, []);

    $uri = $file->getFileUri();
    $extension = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    if (isset($cache[$extension]['bundle']) && isset($cache[$extension]['field_name'])) {
      $bundle = $cache[$extension]['bundle'];
      $media_source_field_name = $cache[$extension]['field_name'];
    }
    else {
      // Attempt to find an appropriate media type based on the file extension.
      /** @var \Drupal\media\MediaTypeInterface[] $media_types */
      $media_types = MediaType::loadMultiple();
      foreach ($media_types as $media_type) {
        $media_source = $media_type->getSource()
          ->getSourceFieldDefinition($media_type);
        if ($media_source) {
          $file_extensions = strtolower($media_source->getSetting('file_extensions'));
          if ($file_extensions) {
            $file_extensions = explode(' ', $file_extensions);
            // Found the allowed extension.
            if (in_array($extension, $file_extensions)) {
              $cache[$extension]['bundle'] = $bundle = $media_type->id();
              $cache[$extension]['field_name'] = $media_source_field_name = $media_source->getName();
              break;
            }
          }
        }
      }
    }

    if (isset($bundle) && isset($media_source_field_name)) {
      $media = Media::create([
        'bundle' => $bundle,
        $media_source_field_name => ['target_id' => $file->id()],
        'name' => $file->getFilename(),
        'uid' => $file->getOwnerId(),
      ] + $media_data);
      $media->save();

      return $media;
    }

    if ($migration instanceof MigrateExecutable) {
      $migration->saveMessage(t('Cannot find a suitable media type for @file', [
        '@file' => $uri,
      ]));
    }

    return NULL;
  }

}
