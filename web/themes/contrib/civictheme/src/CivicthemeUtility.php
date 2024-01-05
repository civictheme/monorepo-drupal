<?php

declare(strict_types=1);

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
  public static function arrayToMultiline($array, $delimiter = PHP_EOL): string {
    return implode($delimiter, array_filter(is_array($array) ? $array : [$array]));
  }

  /**
   * Convert multi-line strings to a list of strings.
   *
   * @param string|array $string
   *   The string to be processed.
   *
   * @return array<string>
   *   A list of strings.
   */
  public static function multilineToArray(string|array $string): array {
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
  public static function camelise($string, $first = FALSE): string {
    $out = '';

    $parts = preg_split('/[\W_]+/', $string) ?: [];
    foreach ($parts as $k => $part) {
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
   * @return array<int|string, mixed>
   *   Array of attributes.
   */
  public static function htmlAttributesToArray(string $string): array {
    $attributes = [];

    if (preg_match_all('/\s*(?:([a-z0-9-]+)\s*=\s*"([^"]*)")|(?:\s+([a-z0-9-]+)(?=\s*|>|\s+[a-z0-9]+))/i', $string, $matches)) {
      $counter = count($matches[0]);
      for ($i = 0; $i < $counter; $i++) {
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
  public static function toLabel(string $string): string {
    return ucfirst(str_replace('_', ' ', $string));
  }

  /**
   * Flatten an array using a separator for keys.
   *
   * @param array $array
   *   The array to flatten.
   * @param string $separator
   *   The key separator.
   * @param string $prefix
   *   Key from the previous call. Internal.
   *
   * @return array<string, mixed>
   *   Flattened one-dimensional array with keys from other arrays.
   *
   * @SuppressWarnings(ElseExpression)
   */
  public static function flattenArray(array $array, string $separator = '.', string $prefix = ''): array {
    $result = [];

    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $result += self::flattenArray($value, $separator, $prefix . $key . $separator);
      }
      else {
        $result[$prefix . $key] = $value;
      }
    }

    return $result;
  }

  /**
   * Merge array keys and values using a separator.
   *
   * Limited only to scalar values.
   *
   * @param array $array
   *   Array to merge.
   * @param string $separator
   *   Optional separator. Defaults to a single space ' '.
   *
   * @return array<string>
   *   Array with merged keys and values.
   */
  public static function arrayMergeKeysValues(array $array, string $separator = ' '): array {
    $result = [];

    foreach ($array as $k => $v) {
      if (!is_scalar($v)) {
        continue;
      }

      $result[] = $k . $separator . $v;
    }

    return $result;
  }

}
