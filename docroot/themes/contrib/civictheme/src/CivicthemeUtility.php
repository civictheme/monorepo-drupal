<?php

namespace Drupal\civictheme;

/**
 * CivicTheme utilities.
 */
final class CivicthemeUtility {

  /**
   * Convert an array to multi-line string value.
   *
   * @param string|array $array
   *   Array to convert.
   * @param string $delimiter
   *   Delimiter to use for multiline. Defaults to PHP_EOL.
   *
   * @return string
   *   String value of the array.
   */
  public static function arrayToMultiline($array, $delimiter = PHP_EOL) {
    return implode($delimiter, array_filter(is_array($array) ? $array : [$array]));
  }

  /**
   * Convert multi-line strings to a list of strings.
   *
   * @param string $string
   *   The string to be processed.
   *
   * @return array
   *   A list of strings.
   */
  public static function multilineToArray($string) {
    $lines = is_array($string) ? $string : explode("\n", str_replace("\r\n", "\n", $string));

    return array_values(array_filter(array_map('trim', $lines)));
  }

  /**
   * Convert a string to a camel case.
   *
   * @param string $string
   *   String to convert.
   * @param bool $first
   *   Capitalize first letter. Defaults to FALSE.
   *
   * @return string
   *   Converted string.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   */
  public static function camelise($string, $first = FALSE) {
    $out = '';

    foreach (preg_split('/[\W_]+/', $string) as $k => $part) {
      $out .= $k === 0 && !$first ? strtolower($part) : ucfirst(strtolower($part));
    }

    return $out;
  }

  /**
   * Parse a string of attributes into an array.
   *
   * @param string $string
   *   String to parse.
   *
   * @return array
   *   Array of attributes.
   */
  public static function htmlAttributesToArray($string) {
    $attributes = [];

    if (preg_match_all('/\s*(?:([a-z0-9-]+)\s*=\s*"([^"]*)")|(?:\s+([a-z0-9-]+)(?=\s*|>|\s+[a-z0-9]+))/i', $string, $matches)) {
      for ($i = 0; $i < count($matches[0]); $i++) {
        if ($matches[3][$i]) {
          $attributes[$matches[3][$i]] = NULL;
          continue;
        }
        $attributes[$matches[1][$i]] = $matches[2][$i];
      }
    }

    return $attributes;
  }

  /**
   * Convert a string to a human-readable label.
   */
  public static function toLabel($string) {
    return ucfirst(str_replace('_', ' ', $string));
  }

}
