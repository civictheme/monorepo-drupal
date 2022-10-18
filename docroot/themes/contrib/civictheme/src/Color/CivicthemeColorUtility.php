<?php

namespace Drupal\civictheme\Color;

/**
 * Color utility.
 *
 * Utility helpers to work with color values.
 */
class CivicthemeColorUtility {

  /**
   * Mix color with another color within a specified percentage range.
   *
   * @param string $color
   *   The origin color.
   * @param string $mixer
   *   The color to mix with.
   * @param int $range
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
  public static function mix($color, $mixer, $range) {
    $color = static::hexToRgb($color);
    $mixer = static::hexToRgb($mixer);
    $range = max(0, min($range, 100));
    $percentage = $range / 100;

    $result = [
      (1 - $percentage) * $color[0] + $percentage * $mixer[0],
      (1 - $percentage) * $color[1] + $percentage * $mixer[1],
      (1 - $percentage) * $color[2] + $percentage * $mixer[2],
    ];

    $result = '#' . self::intToHex($result[0]) . self::intToHex($result[1]) . self::intToHex($result[2]);

    return $result;
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
  public static function normalizeHex($value, $preserve_hash = FALSE) {
    if (!is_string($value)) {
      throw new \Exception(sprintf('Non-string color value provided: %s', print_r($value, TRUE)));
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
   * Convert hexadecimal value to an RGB color rrepresentation.
   *
   * @param string|array $hex
   *   The hexadecimal or RGB (array) color value.
   *
   * @return array
   *   Array with R, G, B values.
   *
   * @throws \Exception
   *   When color value is not in the correct format.
   *
   * @SuppressWarnings(MissingImport)
   * @SuppressWarnings(ElseExpression)
   */
  public static function hexToRgb($hex) {
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
  public static function intToHex($value) {
    $hex = dechex(floor($value));
    if (strlen($hex) === 1) {
      $hex = '0' . $hex;
    }

    return $hex;
  }

}
