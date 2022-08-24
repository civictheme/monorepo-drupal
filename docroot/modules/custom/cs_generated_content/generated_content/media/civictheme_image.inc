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
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_weight() {
  return 12;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_tracking() {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_media_civictheme_image() {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_media_count = 50;

  $entities = [];
  for ($i = 0; $i < $total_media_count; $i++) {
    $file = $helper->staticFile(GeneratedContentAssetGenerator::ASSET_TYPE_JPG);
    $name = sprintf('Demo static Image media %s %s', $i + 1, $helper::staticSentence(2));

    if (!$file) {
      continue;
    }

    $media = Media::create([
      'bundle' => 'civictheme_image',
      'name' => $name,
    ]);

    $media->field_c_m_image->setValue([
      'target_id' => $file->id(),
      'alt' => sprintf('Alt for %s', $name),
    ]);
    $media->field_c_m_caption = $helper::staticSentence(2);

    $media->save();

    $helper::log(
      'Created media Image "%s" [ID: %s] %s',
      Link::createFromRoute($media->getName(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id(),
      Link::createFromRoute('Edit', 'entity.media.edit_form', ['media' => $media->id()])->toString()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
