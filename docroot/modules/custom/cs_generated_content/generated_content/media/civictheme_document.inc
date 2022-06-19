<?php

/**
 * @file
 * Create demo documents.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\media\Entity\Media;

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_document_weight() {
  return 10;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_document_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_media_civictheme_document() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_files_count = 30;

  $filename = sprintf('Demo document file %s', $helper::staticString());
  $file = $helper::createFile(GeneratedContentAssetGenerator::ASSET_TYPE_PDF, [
    'filename' => $filename,
  ], FALSE);

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $variation = $helper::variationRandomValue([
      'status' => NULL,
    ]);

    $name = sprintf('Demo Document media %s %s', $i + 1, $helper::staticSentence(2));

    $media = Media::create([
      'bundle' => 'civictheme_document',
      'name' => $name,
      'status' => $variation['status'],
    ]);

    $media->field_c_m_document->setValue($file->id());

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
