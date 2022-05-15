<?php

namespace Drupal\cs_demo;

use Drupal\Component\Utility\Random;
use Drupal\Component\Utility\Unicode;

/**
 * Class CsDemoRandom.
 *
 * Generic random generators.
 *
 * @package Drupal\cs_demo
 * @SuppressWarnings(PHPMD)
 */
class CsDemoRandom {

  /**
   * Generate a random sentence.
   */
  public static function sentence($min_word_count = 5, $max_word_count = 10) {
    $randomiser = new Random();
    $title = $randomiser->sentences($min_word_count);

    return Unicode::truncate($title, mt_rand($min_word_count, $max_word_count) * 7, TRUE, FALSE, 3);
  }

  /**
   * Generate a random plain text paragraph.
   */
  public static function plainParagraph() {
    $randomiser = new Random();

    return str_replace(["\r", "\n"], '', $randomiser->paragraphs(1));
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
  public static function htmlHeading($min_word_count = 5, $max_word_count = 10, $heading_level = 0, $prefix = '') {
    if (!$heading_level) {
      $heading_level = mt_rand(2, 5);
    }

    return '<h' . $heading_level . '>' . $prefix . self::sentence($min_word_count, $max_word_count) . '</h' . $heading_level . '>';
  }

  /**
   * Generate random HTML paragraphs.
   *
   * @param int $min_paragraph_count
   *   Minimum number of paragraphs to generate.
   * @param int $max_paragraph_count
   *   Maximum number of paragraphs to generate.
   * @param string $prefix
   *   Optional prefix to add to the very first heading.
   *
   * @return string
   *   Paragraphs.
   */
  public static function richText($min_paragraph_count = 3, $max_paragraph_count = 12, $prefix = '') {
    $paragraphs = [];
    $paragraph_count = mt_rand($min_paragraph_count, $max_paragraph_count);
    for ($i = 1; $i <= $paragraph_count; $i++) {
      if ($i % 2) {
        $paragraphs[] = self::htmlHeading(5, 10, $i == 1 ? 2 : rand(2, 4), $prefix);
      }
      $paragraphs[] = self::htmlParagraph();
    }

    return implode(PHP_EOL, $paragraphs);
  }

  /**
   * Generate a random boolean value.
   *
   * @param int $skew
   *   Amount to skew towards one of the values. FALSE is on the left of the
   *   skew line, and TRUE is on the right.
   *   For example, ::bool(33), will have a probability 1/3 for FALSE and
   *   2/3 for TRUE.
   *
   * @return bool
   *   Random value.
   */
  public static function bool($skew = 50) {
    return mt_rand(0, 100) > $skew;
  }

  /**
   * Return a random timestamp.
   */
  public static function timestamp($from = '-1year', $to = "+1year") {
    $from = strtotime($from);
    $to = strtotime($to);

    return mt_rand($from, $to);
  }

  /**
   * Return a random email address.
   *
   * @param string $domain
   *   Optional domain. If not provided, a random domain will be generated.
   *
   * @return string
   *   Random email address.
   */
  public static function email($domain = NULL) {
    $randomiser = new Random();
    $domain = $domain ?? $randomiser->name() . '.com';
    return $randomiser->name() . '@' . $domain;
  }

  /**
   * Generate a random date with or without time.
   *
   * @param string $start
   *   (optional) Start offset in format suitable for strtotime().
   *   Defaults to "now".
   * @param string $finish
   *   (optional) Finish offset in format suitable for strtotime().
   *   Defaults to "now".
   * @param bool $with_time
   *   (optional) Whether or not to include time. Defaults to FALSE.
   *
   * @return string
   *   Random date string with or without time.
   */
  public static function date($start = 'now', $finish = 'now', $with_time = FALSE) {
    $start = strtotime($start);
    $finish = strtotime($finish);

    $start = min($start, $finish);
    $finish = max($start, $finish);

    $format = 'Y-m-d';
    if ($with_time) {
      $format .= '\TH:i:00';
    }

    $timestamp = rand($start, $finish);

    return date($format, $timestamp);
  }

  /**
   * Generate a random date range.
   *
   * @param string $start
   *   Start offset in format suitable for strtotime().
   * @param string $finish
   *   Finish offset in format suitable for strtotime().
   * @param string $format
   *   (optional) Date format. Defaults to 'Y-m-d'.
   *
   * @return array
   *   Array of values suitable for daterange field:
   *   - value: (string) Range start value.
   *   - end_value: (string) Range end value.
   */
  public static function dateRange($start, $finish, $format = 'Y-m-d') {
    $start = strtotime($start);
    $finish = strtotime($finish);

    $start = min($start, $finish);
    $finish = max($start, $finish);

    $start = rand($start, $finish - 1);
    $finish = rand($start + 1, $finish);

    return [
      'value' => date($format, $start),
      'end_value' => date($format, $finish),
    ];
  }

  /**
   * Generate a random 36-character UUID.
   */
  public static function uuid() {
    $data = random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100.
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10.
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }

  /**
   * Helper to get random array items.
   */
  public static function arrayItems($haystack, $count) {
    if ($count == 0) {
      return [];
    }

    $haystack_keys = array_keys($haystack);
    shuffle($haystack_keys);
    $haystack_keys = array_slice($haystack_keys, 0, $count);

    return array_intersect_key($haystack, array_flip($haystack_keys));
  }

  /**
   * Helper to get a single random array item.
   */
  public static function arrayItem($haystack) {
    if (empty($haystack)) {
      return FALSE;
    }

    $items = self::arrayItems($haystack, 1);

    return count($items) > 0 ? reset($items) : FALSE;
  }

  /**
   * Generate random external URL.
   *
   * @param string $domain
   *   (optional) Domain name. Defaults to 'www.example.com'.
   *
   * @return string
   *   URL with a path.
   */
  public static function url($domain = FALSE) {
    $parts = [];
    $parts[] = 'https://';
    $parts[] = $domain ? rtrim($domain, '/') : 'www.example.com';
    $parts[] = '/';
    $parts[] = str_replace(' ', '-', static::sentence());
    return implode('', $parts);
  }

  /**
   * Disperse $fillers within $scope.
   */
  public static function disperse(array $scope, array $fillers) {
    foreach ($fillers as $filler) {
      array_splice($scope, rand(0, count($scope)), 1, $filler);
    }
    return $scope;
  }

  /**
   * Generates a random string.
   */
  public static function string($length = 32) {
    $randomiser = new Random();

    return $randomiser->string($length);
  }

  /**
   * Generates a name.
   */
  public static function name($max = 16) {
    $randomiser = new Random();

    return $randomiser->name(rand(2, $max), TRUE);
  }

  /**
   * Generates a letter abbreviation or a set of letter abbreviations.
   *
   * @param int $length
   *   Length of abbreviation.
   * @param int $count
   *   Count of abbreviations (ensures uniqueness).
   *
   * @return string|string[]
   *   Abbreviation or set of abbreviations.
   */
  public static function abbreviation($length = 2, $count = 1) {
    $randomiser = new Random();

    $abbreviations = [];
    for ($i = 1; $i <= $count; $i++) {
      $abbreviations[] = $randomiser->name($length, TRUE);
    }

    return count($abbreviations) === 1 ? $abbreviations[0] : $abbreviations;
  }

}
