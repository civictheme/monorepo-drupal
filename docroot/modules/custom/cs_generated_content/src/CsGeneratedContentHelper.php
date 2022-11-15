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

  /**
   * Generate a pre-defined static external URL.
   *
   * @param string|bool $domain
   *   (optional) Domain name. Defaults to 'www.example.com'.
   *
   * @return string
   *   URL with a path.
   */
  public static function staticUrl($domain = FALSE) {
    $parts = [];
    $parts[] = 'https://';
    $parts[] = $domain ? rtrim($domain, '/') : 'www.example.com';
    $parts[] = '/';
    $parts[] = str_replace(' ', '-', static::staticSentence());

    return implode('', $parts);
  }

}
