<?php

/**
 * @file
 * Create demo documents.
 */

use Drupal\Core\Link;
use Drupal\media\Entity\Media;
use Drupal\cs_demo\CsDemoHelper;
use Drupal\cs_demo\CsDemoRandom;

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_media_civictheme_document_weight() {
  return 0;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_media_civictheme_document_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_media_civictheme_document() {
  $helper = CsDemoHelper::getInstance();
  /** @var \Drupal\cs_demo\CsGenerator $generator */
  $generator = Drupal::getContainer()->get('cs_demo.generator');

  $total_files_count = 20;

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $variation = CsDemoHelper::variationRandom([
      'status' => NULL,
    ]);
    $variation_info = CsDemoHelper::variationFormatInfo($variation);

    $name = sprintf('Demo Document media %s %s (%s)', $i + 1, CsDemoRandom::sentence(2, 2), $variation_info);
    $filename = preg_replace('/[^\da-zA-Z]/i', '_', $name);

    /** @var \Drupal\file\Entity\File $file */
    $file = $generator->createFromDummyFile([
      'filename' => $filename,
      'extension' => CsDemoRandom::arrayItem([
        'doc',
        'docx',
        'xls',
        'xlsx',
        'pdf',
      ]),
    ]);

    $media = Media::create([
      'bundle' => 'civictheme_document',
      'name' => $name,
      'status' => $variation['status'],
    ]);

    $media->field_c_m_document->setValue($file->id());

    $media->save();

    $helper::log(
      'Created media document for file "%s" (id: %s)',
      Link::createFromRoute($file->getFilename(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
