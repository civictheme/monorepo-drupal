<?php

namespace Drupal\civictheme_migrate;

/**
 * Utilities.
 */
class Utility {

  /**
   * Convert multi-line strings into an array.
   *
   * @param string $string
   *   String value to convert.
   *
   * @return array
   *   Array of values.
   */
  public static function multilineToArray(string $string): array {
    $lines = is_array($string) ? $string : explode("\n", str_replace("\r\n", "\n", $string));

    return array_values(array_filter(array_map('trim', $lines)));
  }

  /**
   * Convert an array to multi-line string value.
   *
   * @param string|array $array
   *   Array to convert.
   *
   * @return string
   *   String value of the array.
   */
  public static function arrayToMultiline(array $array): string {
    $array = is_array($array) ? $array : [$array];

    return implode(PHP_EOL, array_filter($array));
  }

  /**
   * Validate that a file at filepath exists.
   *
   * @param string $filepath
   *   The file path to validate.
   * @param string $message
   *   Optional message.
   * @param array $message_args
   *   Optional array of message arguments.
   */
  public static function validateFileExists(string $filepath, string $message = '', array $message_args = []): void {
    $message = !empty($message) ? $message : 'Unable to find file "%s".';
    $message_args = !empty($message_args) ? $message_args : [$filepath];
    if (!file_exists($filepath)) {
      throw new \RuntimeException(vsprintf($message, $message_args));
    }
  }

}
