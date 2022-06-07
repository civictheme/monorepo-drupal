<?php

namespace Drupal\cs_demo;

use Drupal\Component\Utility\Unicode;

/**
 * Class CsDemoStaticContent.
 *
 * Generic static content generators.
 *
 * @package Drupal\cs_demo
 */
class CsDemoStaticContent {

  /**
   * Generate paragraphs separated by double new line.
   *
   * @param int $paragraph_count
   *   The number of paragraphs to create. Defaults to 10.
   *
   * @return string
   *   Paragraphs.
   */
  public static function paragraphs(int $paragraph_count = 10) {
    $output = [
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in ipsum id orci porta dapibus. Pellentesque in ipsum id orci porta dapibus. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.",
      "Donec rutrum congue leo eget malesuada. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Donec sollicitudin molestie malesuada. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem.",
      "Quisque velit nisi, pretium ut lacinia in, elementum id enim. Sed porttitor lectus nibh. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Cras ultricies ligula sed magna dictum porta.",
      "Donec rutrum congue leo eget malesuada. Donec rutrum congue leo eget malesuada. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.",
      "Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
      "Proin eget tortor risus. Nulla porttitor accumsan tincidunt. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Nulla porttitor accumsan tincidunt.",
      "Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Donec sollicitudin molestie malesuada. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim.",
      "Nulla quis lorem ut libero malesuada feugiat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget tortor risus. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.",
      "Sed porttitor lectus nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula.",
      "Donec sollicitudin molestie malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Nulla quis lorem ut libero malesuada feugiat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.",
    ];

    if ($paragraph_count && $paragraph_count > 10) {
      $paragraph_count = 10;
    }

    $output = array_splice($output, 0, $paragraph_count);

    return implode("\n\n", $output);
  }

  /**
   * Generate a random sentence.
   */
  public static function sentence($word_count = 10) {
    $title = self::paragraphs(1);

    return Unicode::truncate($title, $word_count * 7, TRUE, FALSE, 3);
  }

  /**
   * Generate a random plain text paragraph.
   */
  public static function plainParagraph() {
    $paragraph = self::paragraphs(1);

    return str_replace(["\r", "\n"], '', $paragraph);
  }

  /**
   * Generate a random HTML paragraph.
   */
  public static function htmlParagraph() {
    return '<p>' . self::plainParagraph() . '</p>';
  }

  /**
   * Generate a random HTML heading.
   */
  public static function htmlHeading($word_count = 10, $heading_level = 0, $prefix = '') {
    if (!$heading_level) {
      $heading_level = 3;
    }

    return '<h' . $heading_level . '>' . $prefix . self::sentence($word_count) . '</h' . $heading_level . '>';
  }

  /**
   * Generate random HTML paragraphs.
   *
   * @param int $paragraph_count
   *   Number of paragraphs to generate.
   * @param string $prefix
   *   Optional prefix to add to the very first heading.
   *
   * @return string
   *   Paragraphs.
   */
  public static function richText($paragraph_count = 10, $prefix = '') {
    $paragraphs = [];
    for ($i = 1; $i <= $paragraph_count; $i++) {
      if ($i % 2) {
        $paragraphs[] = self::htmlHeading(8, $i == 1 ? 2 : 3, $prefix);
      }
      $paragraphs[] = self::htmlParagraph();
    }

    return implode(PHP_EOL, $paragraphs);
  }

}
