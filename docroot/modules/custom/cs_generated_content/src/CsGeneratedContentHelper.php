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
   * Select a random real webform.
   *
   * @return \Drupal\webform\Entity\Webform|null
   *   Webform entity object or NULL if no entities were found.
   */
  public static function randomRealWebform() {
    $entities = static::randomRealEntities('webform', NULL, 1);

    return count($entities) > 0 ? reset($entities) : NULL;
  }

}
