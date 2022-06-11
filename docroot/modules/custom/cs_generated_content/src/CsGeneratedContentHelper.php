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

  /**
   * Get static demo media of the specified bundle.
   *
   * @param string $bundle
   *   Bundle machine name.
   * @param int $count
   *   Optional media count to return.
   * @param int $offset
   *   Optional offset of the number of media from the beginning.
   *
   * @return \Drupal\taxonomy\Entity\Term[]
   *   Array of media.
   */
  public static function staticMedia($bundle, $count = NULL, $offset = 0) {
    $items = self::$repository->getEntities('media', $bundle);
    $offset = min(count($items), $offset);

    return !is_null($count) ? array_slice($items, $offset, $count) : $items;
  }

  /**
   * Get static demo terms from the specified vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine name.
   * @param int $count
   *   Optional term count to return.
   * @param int $offset
   *   Optional offset of the number of terms from the beginning.
   *
   * @return \Drupal\taxonomy\Entity\Term[]
   *   Array of terms.
   */
  public static function staticTerms($vid, $count = NULL, $offset = 0) {
    $terms = self::$repository->getEntities('taxonomy_term', $vid);
    $offset = min(count($terms), $offset);

    return !is_null($count) ? array_slice($terms, $offset, $count) : $terms;
  }

  /**
   * {@inheritdoc}
   *
   * Fixed method in the parent class incorrectly passing through $context_id
   * to heading and paragraphs.
   */
  public static function staticRichText($paragraphs = 10, $prefix = '', $content_idx = 0) {
    $content = [];
    for ($i = 1; $i <= $paragraphs; $i++) {
      if ($i % 2) {
        $content[] = static::staticHtmlHeading(8, $i == 1 ? 2 : 3, $prefix, $content_idx + $i);
      }
      $content[] = static::staticHtmlParagraph($content_idx + $i + 1);
    }

    return implode(PHP_EOL, $content);
  }

}
