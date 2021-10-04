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
function _civic_media_image(MediaInterface $media) {
  if ($media instanceof Media) {
    $fid = $media->getSource()->getSourceFieldValue($media);
    $config = $media->getSource()->getConfiguration();
    $image_entity = $media->get($config['source_field'])->first()->getValue();

    $file = File::load($fid);
    if ($file) {
      // @todo fix image style generation from static content.
      $image['src'] = $file->createFileUrl();
      $image['alt'] = !empty($image_entity['alt']) ? $image_entity['alt'] : NULL;
      $image['title'] = !empty($image_entity['title']) ? $image_entity['title'] : NULL;
      return $image;
    }
  }
}
