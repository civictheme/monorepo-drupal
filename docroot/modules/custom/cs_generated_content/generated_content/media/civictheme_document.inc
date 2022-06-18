<?php

/**
 * @file
 * Create demo documents.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
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

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $variation = $helper::variationRandom([
      'status' => NULL,
    ]);

    $name = sprintf('Demo Document media %s %s', $i + 1, $helper::staticSentence(2, $i + 1));
    $filename = preg_replace('/[^\da-zA-Z]/i', '_', $name);

    /** @var \Drupal\file\Entity\File $file */
    $file = $helper::staticFile([
      'filename' => $filename,
      'extension' => 'pdf',
    ]);

    $media = Media::create([
      'bundle' => 'civictheme_document',
      'name' => $name,
      'status' => $variation['status'],
    ]);

    $media->field_c_m_document->setValue($file->id());

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
