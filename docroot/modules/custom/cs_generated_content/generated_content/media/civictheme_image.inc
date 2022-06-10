<?php

/**
 * @file
 * Create demo images.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\media\Entity\Media;

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_weight() {
  return 0;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_media_civictheme_image() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_files_count = 50;

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $filename = sprintf('Demo Image media %s %s', $i + 1, $helper::staticSentence(2, $i + 1));
    /** @var \Drupal\file\Entity\File $file */
    $file = $helper::staticFile([
      'filename' => $filename,
      'extension' => 'png',
    ]);
    $media = Media::create([
      'bundle' => 'civictheme_image',
      'name' => $filename,
    ]);

    $media->field_c_m_image->setValue($file->id());
    $media->field_c_m_image->alt = sprintf('Alt for %s', $filename);
    $media->field_c_m_caption = $helper::staticSentence(2, $i + 1);
    $media->save();

    $helper::log(
      'Created media %s for file "%s" [ID: %s] %s',
      $media->bundle(),
      Link::createFromRoute($file->getFilename(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id(),
      Link::createFromRoute('Edit', 'entity.media.edit_form', ['media' => $media->id()])->toString()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
