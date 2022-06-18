<?php

/**
 * @file
 * Create demo icons.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\media\Entity\Media;

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_icon_weight() {
  return 11;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_icon_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_media_civictheme_icon() {
  $helper = CsGeneratedContentHelper::getInstance();

  $total_files_count = 20;

  $filename = sprintf('Demo icon file %s', $helper::staticString());
  $file = $helper::createFile($helper::FILE_TYPE_BINARY, [
    'filename' => $filename,
    'extension' => 'svg',
  ], FALSE);

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $name = sprintf('Demo Icon media %s %s', $i + 1, $helper::staticSentence(2));

    $media = Media::create([
      'bundle' => 'civictheme_icon',
      'name' => $name,
    ]);

    $media->field_c_m_icon->setValue($file->id());
    $media->save();

    $helper::log(
      'Created media %s "%s" [ID: %s] %s',
      $media->bundle(),
      Link::createFromRoute($media->getName(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id(),
      Link::createFromRoute('Edit', 'entity.media.edit_form', ['media' => $media->id()])->toString()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
