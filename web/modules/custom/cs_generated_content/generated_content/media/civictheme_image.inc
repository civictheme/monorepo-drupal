<?php

/**
 * @file
 * Create generated images.
 */

declare(strict_types=1);

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\media\Entity\Media;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_weight(): int {
  return 12;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_image_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function cs_generated_content_generated_content_create_media_civictheme_image(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_media_count = 50;

  $entities = [];
  for ($i = 0; $i < $total_media_count; $i++) {
    $file = $helper->staticFile(GeneratedContentAssetGenerator::ASSET_TYPE_JPG);
    $name = sprintf('Generated static Image media %s %s', $i + 1, $helper::staticSentence(2));

    if ($file === NULL) {
      continue;
    }

    $media = Media::create([
      'bundle' => 'civictheme_image',
      'name' => $name,
      'moderation_state' => 'published',
    ]);

    $media->field_c_m_image->setValue([
      'target_id' => $file->id(),
      'alt' => sprintf('Alt for %s', $name),
    ]);
    // @phpstan-ignore-next-line
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
