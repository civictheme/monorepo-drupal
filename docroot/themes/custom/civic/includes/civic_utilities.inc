<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

/**
 * Process media to generate image array for twig.
 *
 * @param int $media_target_id
 *   Target ID from reference.
 * @param string $media_field
 *   Field name for the media target.
 *
 * @return array|false
 *   Image array or false if not available.
 *
 * @throws \Drupal\Core\TypedData\Exception\MissingDataException
 */
function _civic_media_image($media_target_id, $media_field = 'field_media_image') {
  if (isset($media_target_id)) {
    /** @var \Drupal\media\Entity\Media $media */
    if ($media = Media::load($media_target_id)) {
      $file = File::load($media->get($media_field)->first()->getValue()['target_id']);
      // @todo fix image style generation from static content.
      $image['src'] = file_create_url($file->getFileUri());
      $image['alt'] = $media->get($media_field)->first()->getValue()['alt'] ?? '';
      return $image;
    }
  }
  return FALSE;
}
