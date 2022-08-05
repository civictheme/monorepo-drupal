<?php

namespace Drupal\cs_generated_content;

use Drupal\generated_content\Helpers\GeneratedContentHelper;
use Drupal\file\FileInterface;

/**
 * Class CsGeneratedContentHelper.
 *
 * Helper to provision CivicTheme content.
 *
 * @package \Drupal\cs_generated_content
 */
class CsGeneratedContentHelper extends GeneratedContentHelper {

  use CsGeneratedContentCivicthemeTrait;

  /**
   * Select a random real webform.
   *
   * @return \Drupal\webform\Entity\Webform|null
   *   Webform entity object or NULL if no entities were found.
   */
  public static function randomRealWebform() {
    $entities = static::randomRealEntities('webform', NULL, 1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

  /**
   * Get image variation.
   *
   * @param \Drupal\file\FileInterface $file
   *   File entity.
   * @param int $scale
   *   Scale constant.
   *
   * @return \Drupal\file\FileInterface
   *   Created managed file.
   */
  public static function resizeImagePng(FileInterface $file, $scale) {
    $file_system = \Drupal::service('file_system');
    $path = $file_system->realpath($file->getFileUri());
    $imageinfo = getimagesize($path);

    if (isset($imageinfo[2]) && $imageinfo[2] == IMAGETYPE_PNG) {
      $image = imagecreatefrompng($path);

      $width = (imagesx($image) > 2000 ? imagesx($image) : 2000) * $scale / 10;
      $height = (imagesy($image) > 1000 ? imagesx($image) : 1000) * $scale / 10;

      $newimage = imagecreatetruecolor($width, $height);
      imagecopyresampled($newimage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
      $tmp_file = $file_system->tempnam('temporary://', 'generateImage_');
      $filepath = $file_system->realpath($tmp_file);
      imagepng($newimage, $filepath);

      $uri = "public://generated_content/" . basename($path, '.png') . '-' . $scale . '.png';
      return \Drupal::service('file.repository')->writeData(file_get_contents($filepath), $uri);
    }

    return FALSE;
  }

}
