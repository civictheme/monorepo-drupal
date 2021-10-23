<?php

/**
 * @file
 * Media theme alterations.
 */

/**
 * Implements hook_preprocess__HOOK().
 */
function civic_preprocess_media__image(&$variables) {
  if (empty($variables['media'])) {
    return;
  }
  $media = $variables['media'];
  $image = $media->get('field_media_image')->referencedEntities()[0];
  $variables['caption'] = $media->get('field_c_m_caption')->getString();
  $variables['src'] = $image->createFileUrl();
  $variables['alt'] = $media->get('field_media_image')->first()->get('alt')->getString();
}
