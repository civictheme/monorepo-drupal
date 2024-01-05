<?php

/**
 * @file
 * Create generated icons.
 */

use Drupal\Core\Link;
use Drupal\cs_generated_content\CsGeneratedContentHelper;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\media\Entity\Media;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_media_civictheme_icon_weight(): int {
  return 11;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_media_civictheme_icon_tracking(): bool {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 *
 * @phpstan-ignore-next-line
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function cs_generated_content_generated_content_create_media_civictheme_icon(): array {
  /** @var \Drupal\cs_generated_content\CsGeneratedContentHelper $helper */
  $helper = CsGeneratedContentHelper::getInstance();

  $total_media_count = 20;

  $entities = [];
  for ($i = 0; $i < $total_media_count; $i++) {
    $file = $helper->staticFile(GeneratedContentAssetGenerator::ASSET_TYPE_SVG);
    $name = sprintf('Generated static Icon media %s %s', $i + 1, $helper::staticSentence(2));

    if ($file === NULL) {
      continue;
    }

    $media = Media::create([
      'bundle' => 'civictheme_icon',
      'name' => $name,
    ]);

    $media->field_c_m_icon->setValue($file->id());

    $media->save();

    $helper::log(
      'Created media Icon "%s" [ID: %s] %s',
      Link::createFromRoute($media->getName(), 'entity.media.canonical', ['media' => $media->id()])->toString(),
      $media->id(),
      Link::createFromRoute('Edit', 'entity.media.edit_form', ['media' => $media->id()])->toString()
    );

    $entities[$media->id()] = $media;
  }

  return $entities;
}
