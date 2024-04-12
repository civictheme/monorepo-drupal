<?php

declare(strict_types=1);

namespace Drupal\civictheme\Color;

/**
 * Color utility.
 *
 * Utility helpers to work with color values.
 */
class CivicthemeColorUtility {

  /**
   * Defines CSS color keywords.
   *
   * @see https://www.w3.org/TR/css-color-3/
   */
  final const KEYWORDS = [
    'black' => '#000000',
    'silver' => '#C0C0C0',
    'gray' => '#808080',
    'white' => '#FFFFFF',
    'maroon' => '#800000',
    'red' => '#FF0000',
    'purple' => '#800080',
    'fuchsia' => '#FF00FF',
    'green' => '#008000',
    'lime' => '#00FF00',
    'olive' => '#808000',
    'yellow' => '#FFFF00',
    'navy' => '#000080',
    'blue' => '#0000FF',
    'teal' => '#008080',
    'aqua' => '#00FFFF',
  ];

  /**
   * Convert color keyword to hex value.
   *
   * @param string $value
   *   The value (keyword) to convert.
   *
   * @return string
   *   Converted value if such keyword exists or original value otherwise.
   */
  public static function keywordToHex($value) {
    return self::KEYWORDS[$value] ?? $value;
  }

  /**
   * Mix color with another color within a specified percentage range.
   *
   * @param string|array $color
   *   The origin color.
   * @param string|array $mixer
   *   The color to mix with.
   * @param string|int $range
   *   Range to apply the mixer color with. From 0 to 100.
   *
   * @return string
   *   the mixed color.
   *
   * @throws \Exception
   *   When color value is not valid.
   *
   * @SuppressWarnings(MissingImport)
   */
  public static function mix(string|array $color, string|array $mixer, string|int $range): string {
    if (!is_numeric($range)) {
      throw new \Exception(sprintf('Numeric value is expected for range, but %s provided.', $range));
    }

    $range = (int) $range;

    $color = static::hexToRgb($color);
    $mixer = static::hexToRgb($mixer);
    $range = max(0, min($range, 100));
    $percentage = $range / 100;

    $result = [
      (1 - $percentage) * $color[0] + $percentage * $mixer[0],
      (1 - $percentage) * $color[1] + $percentage * $mixer[1],
      (1 - $percentage) * $color[2] + $percentage * $mixer[2],
    ];

    return '#' . self::intToHex($result[0]) . self::intToHex($result[1]) . self::intToHex($result[2]);
  }

  /**
   * Normalize color value.
   *
   * @param string $value
   *   The color value as 3 or 6 hexadecimal characters with or without
   *   leading '#'.
   * @param bool $preserve_hash
   *   Optional flag to preserve hash when normalizing. Defaults to FALSE.
   *
   * @return string
   *   Normalized color value.
   *
   * @throws \Exception
   *   When color value is not in the correct format.
   *
   * @SuppressWarnings(MissingImport)
   * @SuppressWarnings(BooleanArgumentFlag)
   */
  public static function normalizeHex($value, $preserve_hash = FALSE): string {
    if (!is_string($value)) {
      throw new \Exception(sprintf('Non-string color value provided: %s', var_export($value, TRUE)));
    }

    $value = str_starts_with($value, '#') && !$preserve_hash ? substr($value, 1) : $value;

    if (strlen($value) !== 3 && strlen($value) !== 6) {
      throw new \Exception(sprintf('Invalid color value format provided: %s', $value));
    }

    if (strlen($value) === 3) {
      $value = $value[0] . $value[0] . $value[1] . $value[1] . $value[2] . $value[2];
    }

    $value = strtolower($value);

    if (!preg_match('/[0-9a-f]{6}/', $value)) {
      throw new \Exception(sprintf('Value is not hexadecimal a color: %s', $value));
    }

    return $value;
  }

  /**
   * Convert hexadecimal value to an RGB color representation.
   *
   * @param string|array $hex
   *   The hexadecimal or RGB (array) color value.
   *
   * @return array<int>
   *   Array with R, G, B values.
   *
   * @throws \Exception
   *   When color value is not in the correct format.
   *
   * @SuppressWarnings(MissingImport)
   * @SuppressWarnings(ElseExpression)
   */
  public static function hexToRgb(string|array $hex): array {
    $rgb = [];
    if (is_string($hex)) {
      $hex = trim($hex);

      if (str_starts_with($hex, '#')) {
        $hex = static::normalizeHex($hex);
      }

      $rgb[] = hexdec($hex[0] . $hex[1]);
      $rgb[] = hexdec($hex[2] . $hex[3]);
      $rgb[] = hexdec($hex[4] . $hex[5]);
    }
    elseif (is_array($hex)) {
      if (count($hex) == 3) {
        $rgb = $hex;
      }
    }
    else {
      throw new \Exception('Unsupported color value provided.');
    }

    return $rgb;
  }

  /**
   * Convert integer into hexadecimal string value.
   *
   * @param int $value
   *   Integer value.
   *
   * @return string
   *   Hexadecimal value.
   */
  public static function intToHex($value): string {
    $hex = dechex((int) floor($value));
    if (strlen($hex) === 1) {
      $hex = '0' . $hex;
    }

    return $hex;
  }

}
