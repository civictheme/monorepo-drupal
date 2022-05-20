<?php

/**
 * @file
 * Create demo images.
 */

use Drupal\Core\Link;
use Drupal\media\Entity\Media;
use Drupal\cs_demo\CsDemoHelper;
use Drupal\cs_demo\CsDemoRandom;

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_demo_create_media_civictheme_image_weight() {
  return 0;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_demo_create_media_civictheme_image_tracking() {
  return TRUE;
}

/**
 * Implements hook_cs_demo_create_ENTITY_TYPE_BUNDLE().
 */
function cs_demo_create_media_civictheme_image() {
  $helper = CsDemoHelper::getInstance();
  /** @var \Drupal\cs_demo\CsGenerator $generator */
  $generator = Drupal::getContainer()->get('cs_demo.generator');

  $total_files_count = 20;

  $entities = [];
  for ($i = 0; $i < $total_files_count; $i++) {
    $filename = sprintf('Demo image %s %s', $i + 1, CsDemoRandom::sentence(2, 2));
    /** @var \Drupal\file\Entity\File $file */
    $file = $generator->createImage([
      'filename' => $filename,
      'width' => 2000,
      'height' => 1000,
      'format' => CsDemoRandom::arrayItem(['png', 'jpg']),
    ]);

    $media = Media::create([
      'bundle' => 'civictheme_image',
      'name' => $filename,
    ]);

    $media->field_c_m_image->setValue($file->id());
    $media->field_c_m_image->alt = sprintf('Alt for %s', $filename);
    $media->field_c_m_caption = CsDemoRandom::sentence(0, 10);
    $media->save();

    $helper::log(
      'Created media image for file "%s" (id: %s)',
      Link::createFromRoute($file->getFilename(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
