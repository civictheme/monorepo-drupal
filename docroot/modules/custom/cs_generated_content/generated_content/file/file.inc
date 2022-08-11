<?php

/**
 * @file
 * Create generated files.
 */

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;
use Drupal\generated_content\Helpers\GeneratedContentHelper;

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_weight().
 */
function cs_generated_content_generated_content_create_file_file_weight() {
  return -10;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE_tracking().
 */
function cs_generated_content_generated_content_create_file_file_tracking() {
  return TRUE;
}

/**
 * Implements hook_generated_content_create_ENTITY_TYPE_BUNDLE().
 */
function cs_generated_content_generated_content_create_file_file() {
  /** @var \Drupal\generated_content\Helpers\GeneratedContentHelper $helper */
  $helper = GeneratedContentHelper::getInstance();

  $total_files_count_per_type = 10;

  $entities = [];

  $generation_types = [
    GeneratedContentAssetGenerator::GENERATE_TYPE_STATIC => [
      GeneratedContentAssetGenerator::ASSET_TYPE_JPG,
      GeneratedContentAssetGenerator::ASSET_TYPE_SVG,
      GeneratedContentAssetGenerator::ASSET_TYPE_PDF,
    ],
  ];

  foreach ($generation_types as $generation_type => $asset_types) {
    foreach ($asset_types as $asset_type) {
      for ($i = 0; $i < $total_files_count_per_type; $i++) {
        $filename = sprintf('Demo %s %s file %s %s', $generation_type, $asset_type, $i + 1, $helper::randomName(4));
        $file = $helper->createFile($asset_type, [
          'filename' => str_replace(' ', '_', $filename),
        ], $generation_type);

        $link_options = ['attributes' => ['target' => '_blank']];
        $helper::log(
          'Created file "%s" [ID: %s]',
          Link::fromTextAndUrl($file->getFilename(), Url::fromUri($file->createFileUrl(FALSE), $link_options))->toString(),
          $file->id(),
        );
        $entities[$file->id()] = $file;
      }
    }
  }

  return $entities;
}
