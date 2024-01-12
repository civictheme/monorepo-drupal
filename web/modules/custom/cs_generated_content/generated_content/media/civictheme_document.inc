<?php

/**
 * @file
 * Create generated documents.
 */

declare(strict_types=1);

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\media\Entity\Media;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_document_weight(): int {
  return 10;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_document_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function cs_generated_content_generated_content_create_media_civictheme_document(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_media_count = 30;

  $entities = [];
  for ($i = 0; $i < $total_media_count; $i++) {
    $variation = $helper::variationRandomValue([
      'status' => NULL,
    ]);

    $file = $helper->staticFile(GeneratedContentAssetGenerator::ASSET_TYPE_PDF);
    $name = sprintf('Generated static Document media %s %s', $i + 1, $helper::staticSentence(2));

    if ($file === NULL) {
      continue;
    }

    $media = Media::create([
      'bundle' => 'civictheme_document',
      'name' => $name,
      'status' => $variation['status'],
    ]);

    $media->field_c_m_document->setValue($file->id());

    $media->save();

    $helper::log(
      'Created media Document "%s" [ID: %s] %s',
      Link::createFromRoute($media->getName(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id(),
      Link::createFromRoute('Edit', 'entity.media.edit_form', ['media' => $media->id()])->toString()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
