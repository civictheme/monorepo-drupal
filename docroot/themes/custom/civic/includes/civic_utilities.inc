<?php

/**
 * @file
 * Theme utility functions.
 */

use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\media\MediaInterface;

/**
 * Process media to generate image array for twig.
 *
 * @param \Drupal\media\MediaInterface $media
 *   Host Media entity for image.
 * @param string $media_field
 *   Field name for the media target.
 *
 * @return array
 *   Image array or false if not available.
 */
function _civic_media_image(MediaInterface $media, $media_field = 'field_media_image') {
  if ($media instanceof Media) {
    $file = File::load($media->get($media_field)->first()->getValue()['target_id']);
    // @todo fix image style generation from static content.
    $image['src'] = file_create_url($file->getFileUri());
    $image['alt'] = $media->get($media_field)->first()->getValue()['alt'] ?? '';

    return $image;
  }
}
