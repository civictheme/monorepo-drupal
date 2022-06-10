<?php

namespace Drupal\cs_generated_content;

use Drupal\generated_content\Helpers\GeneratedContentHelper;

/**
 * Class CsGeneratedContentHelper.
 *
 * Helper to provision CivicTheme content.
 *
 * @package \Drupal\cs_generated_content
 */
class CsGeneratedContentHelper extends GeneratedContentHelper {

  use CsGeneratedContentCivicthemeTrait;

  /**
   * Generate static file from existing file assets.
   */
  public static function staticFile($options) {
    return self::$assetGenerator->createFromDummyFile($options);
  }

}
