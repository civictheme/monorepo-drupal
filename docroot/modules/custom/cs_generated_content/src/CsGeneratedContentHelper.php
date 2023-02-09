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

  /**
   * Generate a pre-defined static sentence as a field value.
   *
   * @param int $count
   *   Number of words.
   * @param string $format
   *   Text format. Defaults to 'civictheme_rich_text'.
   *
   * @return array
   *   Array of static content string as an element with 'value' key and format
   *   as en element with 'format' key.
   */
  public static function staticSentenceFieldValue($count = 5, $format = 'civictheme_rich_text') {
    return [
      'value' => static::staticSentence($count),
      'format' => $format,
    ];
  }

  /**
   * Generate a pre-defined static link as a field value.
   *
   * @param int $count
   *   Number of words.
   *
   * @return array
   *   Array of static link URI and content string.
   */
  public static function staticLinkFieldValue($count = 2) {
    return [
      'uri' => static::staticUrl(),
      'title' => static::staticSentence($count),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function staticRichText($paragraphs = 4, $prefix = '') {
    $content = parent::staticRichText($paragraphs, $prefix);
    $content .= '<a href="' . self::staticUrl() . '">' . self::staticSentence(2) . '</a>';

    return $content;
  }

  /**
   * Convert date string to formatted date in UTC timezone.
   */
  public static function dateToUtc($date, $format = 'Y-m-d\TH:i:s') {
    $datetime = new \DateTime($date);
    $datetime->setTimezone(new \DateTimeZone('UTC'));

    return $datetime->format($format);
  }

}
