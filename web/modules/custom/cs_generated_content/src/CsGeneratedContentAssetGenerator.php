<?php

declare(strict_types=1);

namespace Drupal\cs_generated_content;

use Drupal\generated_content\Helpers\GeneratedContentAssetGenerator;

/**
 * Class CsGeneratedContentAssetGenerator.
 *
 * Extended generated_content generator.
 *
 * @package Drupal\generated_content
 */
class CsGeneratedContentAssetGenerator extends GeneratedContentAssetGenerator {

  /**
   * {@inheritdoc}
   */
  protected function getAssetsDirs(): array {
    $module_path = $this->moduleExtensionList->getPath('cs_generated_content');

    return [
      $module_path . DIRECTORY_SEPARATOR . rtrim((string) static::ASSETS_DIRECTORY, DIRECTORY_SEPARATOR),
    ];
  }

}
