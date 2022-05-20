<?php

namespace Drupal\cs_demo;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\File\FileSystemInterface;

/**
 * Class CsGenerator.
 *
 * The utility class for generating data.
 *
 * @package Drupal\cs_demo
 * @SuppressWarnings(PHPMD)
 */
class CsGenerator {

  /**
   * Defines assets directory.
   */
  const ASSETS_DIRECTORY = 'assets';

  /**
   * The utility class for creating random data.
   *
   * @var \Drupal\Component\Utility\Random
   */
  protected $random;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Array of available assets.
   *
   * Keys are extensions without a leading dot and values are paths to assets.
   *
   * @var array
   */
  protected $assets;

  /**
   * Constructor.
   */
  public function __construct(FileSystemInterface $file_system, EntityTypeManager $entity_type_manager) {
    $this->fileSystem = $file_system;
    $this->entityTypeManager = $entity_type_manager;
    $this->random = new Random();
    $this->assets = $this->loadAssets();
  }

  /**
   * Create an image and store it as a managed file.
   *
   * Similar to Random::image(), but has multiple optimisations.
   *
   * @param array $options
   *   Array of options for generation:
   *   - filename: (string) Optional image file name. If not provided,
   *     random name will be used.
   *   - width: (int) Image width.
   *   - height: (int) Image height.
   *   - format: (string) One of the following: gif, jpg, png. Defaults
   *     to 'png'.
   * @param bool $use_existing
   *   (optional) Flag to use existing image. Defaults to TRUE.
   *
   * @return bool|object
   *   Created file object or FALSE if there was an error creating or saving an
   *   image.
   */
  public function createImage(array $options = [], $use_existing = TRUE) {
    $options += [
      'width' => 350,
      'height' => 200,
      'format' => 'png',
      'filename' => '',
    ];

    // Normalise options.
    $width = $options['width'];
    $height = $options['height'];
    $extension = $options['format'];
    $options['format'] = '.' . ltrim($options['format'], '.');

    // Provide random file name.
    $filename = !empty($options['filename']) ?
      $options['filename'] . $options['format']
      : $this->random->word(rand(4, 12)) . $options['format'];

    // Find existing files.
    if ($use_existing) {
      if ($file = $this->findFileByName($filename)) {
        return $file;
      }
    }

    $dir = 'private://demo/';
    $this->fileSystem->prepareDirectory($dir, FileSystemInterface::CREATE_DIRECTORY);

    $uri = $dir . $filename;
    // Make sure that there is an extension.
    $uri = empty(pathinfo($uri, PATHINFO_EXTENSION)) ? $uri . $options['format'] : $uri;

    if (!$tmp_file = $this->fileSystem->tempnam('temporary://', 'imagefield_')) {
      throw new \RuntimeException('Unable to generate an image');
    }

    $destination = $tmp_file . '.' . $extension;
    $this->fileSystem->move($tmp_file, $destination, FileSystemInterface::CREATE_DIRECTORY);

    // Make an image split into 4 sections with random colors.
    $im = imagecreate($width, $height);
    for ($n = 0; $n < 4; $n++) {
      $color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
      $x = $width / 2 * ($n % 2);
      $y = $height / 2 * (int) ($n >= 2);
      imagefilledrectangle($im, $x, $y, $x + $width / 2, $y + $height / 2, $color);
    }

    // Make a perfect circle in the image middle.
    $color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
    $smaller_dimension = min($width, $height);
    $smaller_dimension = ($smaller_dimension % 2) ? $smaller_dimension : $smaller_dimension;
    imageellipse($im, $width / 2, $height / 2, $smaller_dimension, $smaller_dimension, $color);

    $save_function = 'image' . ($extension == 'jpg' ? 'jpeg' : $extension);
    $save_function($im, $this->fileSystem->realpath($destination));

    $file = file_save_data(file_get_contents($destination), $uri);

    return $file;
  }

  /**
   * Generate a placeholder flat file and store it as a managed file.
   *
   * @param array $options
   *   Array of options for generation:
   *   - filename: (string) Optional image file name. If not provided,
   *     random name will be used.
   *   - content: (string) The content of the file.
   *   - extension: (string) The extension of the file.
   * @param bool $use_existing
   *   (optional) Flag to use existing file. Defaults to TRUE.
   *
   * @return bool|object
   *   Created file object or FALSE if there was an error creating or saving
   *   a file.
   */
  public function createFlatFile(array $options = [], $use_existing = TRUE) {
    $options += [
      'content' => 'Placeholder text',
      'filename' => '',
      'extension' => 'txt',
    ];

    $options['extension'] = '.' . ltrim($options['extension'], '.');

    // Provide random file name.
    $filename = !empty($options['filename']) ?
      $options['filename'] . $options['extension']
      : $this->random->word(rand(4, 12)) . $options['extension'];

    // Find existing files.
    if ($use_existing) {
      if ($file = $this->findFileByName($filename)) {
        return $file;
      }
    }

    $dir = 'private://demo/';
    $this->fileSystem->prepareDirectory($dir, FileSystemInterface::CREATE_DIRECTORY);

    $uri = $dir . $filename;
    // Make sure that there is an extension.
    $uri = empty(pathinfo($uri, PATHINFO_EXTENSION)) ? $uri . $options['extension'] : $uri;
    $file = file_save_data($options['content'], $uri);

    return $file;
  }

  /**
   * Generate a placeholder file from existing dummy asset file.
   *
   * @param array $options
   *   Array of options for generation:
   *   - filename: (string) Optional image file name. If not provided,
   *     random name will be used.
   *   - extension: (string) The extension of the file.
   * @param bool $use_existing
   *   (optional) Flag to use existing file. Defaults to TRUE.
   *
   * @return bool|object
   *   Created file object or FALSE if there was an error creating or saving
   *   a file.
   */
  public function createFromDummyFile(array $options = [], $use_existing = TRUE) {
    $options += [
      'filename' => '',
      'extension' => 'txt',
    ];

    $options['extension'] = ltrim($options['extension'], '.');

    if (empty($this->assets[$options['extension']])) {
      throw new \Exception(sprintf('No dummy asset is available for extension %s.', $options['extension']));
    }

    $asset_path = $this->assets[$options['extension']];

    // Provide random file name.
    $filename = !empty($options['filename']) ?
      $options['filename'] . '.' . $options['extension']
      : $this->random->word(rand(4, 12)) . '.' . $options['extension'];

    // Find existing files.
    if ($use_existing) {
      if ($file = $this->findFileByName($filename)) {
        return $file;
      }
    }

    $dir = 'private://demo/';
    $this->fileSystem->prepareDirectory($dir, FileSystemInterface::CREATE_DIRECTORY);

    $uri = $dir . $filename;
    // Make sure that there is an extension.
    $uri = empty(pathinfo($uri, PATHINFO_EXTENSION)) ? $uri . '.' . $options['extension'] : $uri;
    $file = file_save_data(file_get_contents($asset_path), $uri);

    return $file;
  }

  /**
   * Helper to find file by name.
   *
   * @param string $filename
   *   Filename to search for.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\file\Entity\File|null
   *   File object or NULL if not found.
   */
  protected function findFileByName($filename) {
    $file = NULL;

    $storage = $this->entityTypeManager->getStorage('file');
    $query = $storage->getQuery('AND')->condition('filename', $filename);
    $ids = $query->execute();

    if (!empty($ids)) {
      $id = reset($ids);
      $file = $storage->load($id);
    }

    return $file;
  }

  /**
   * Load assets.
   *
   * @return array
   *   Array with extensions (without leading dot) as keys and paths to dummy
   *   asset files as values.
   */
  protected function loadAssets() {
    // Pre-load replacement assets.
    $extensions = [
      'jpg',
      'jpeg',
      'gif',
      'png',
      'pdf',
      'doc',
      'docx',
      'xls',
      'xlsx',
      'mp3',
      'mp4',
      'svg',
    ];

    $module_path = drupal_get_path('module', 'cs_demo');
    foreach ($extensions as $extension) {
      $dummy_file = $module_path . DIRECTORY_SEPARATOR . rtrim(self::ASSETS_DIRECTORY, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'dummy.' . $extension;
      if (file_exists($dummy_file)) {
        $assets[$extension] = $dummy_file;
      }
    }

    return $assets;
  }

}
