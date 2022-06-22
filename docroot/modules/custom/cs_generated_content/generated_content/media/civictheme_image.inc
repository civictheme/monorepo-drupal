<?php

/**
 * @file
 * Create demo images.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\media\Entity\Media;

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_weight() {
  return 12;
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

  $filename = sprintf('Demo image file %s', $helper::staticSentence(2));
  $file = $helper::createFile(GeneratedContentAssetGenerator::ASSET_TYPE_PNG, [
    'filename' => $filename,
  ], GeneratedContentAssetGenerator::GENERATE_TYPE_STATIC);

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $name = sprintf('Demo Image media %s %s', $i + 1, $helper::staticSentence(2));

    $media = Media::create([
      'bundle' => 'civictheme_image',
      'name' => $name,
    ]);

    $media->field_c_m_image->setValue($file->id());
    $media->field_c_m_image->alt = sprintf('Alt for %s', $name);
    $media->field_c_m_caption = $helper::staticSentence(2);
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
