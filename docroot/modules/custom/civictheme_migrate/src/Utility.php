<?php

namespace Drupal\civictheme_migrate;

/**
 * Utilities.
 */
class Utility {

  /**
   * Convert multi-line strings into an array.
   *
   * @param string|array $input
   *   Input value to convert.
   *
   * @return array
   *   Array of values.
   */
  public static function multilineToArray(string|array $input): array {
    $lines = is_array($input) ? $input : explode("\n", str_replace("\r\n", "\n", $input));

    return array_values(array_filter(array_map('trim', $lines)));
  }

  /**
   * Convert an array to multi-line string value.
   *
   * @param string|array $input
   *   Input value to convert.
   *
   * @return string
   *   String value of the array.
   */
  public static function arrayToMultiline(string|array $input): string {
    $input = is_array($input) ? $input : [$input];

    return implode(PHP_EOL, array_filter($input));
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
   *
   * @throw \RuntimeException
   *   If the file does not exist.
   */
  public static function validateFileExists(string $filepath, string $message = '', array $message_args = []): void {
    $message = !empty($message) ? $message : 'Unable to find file "%s".';
    $message_args = !empty($message_args) ? $message_args : [$filepath];
    if (!file_exists($filepath)) {
      throw new \RuntimeException(vsprintf($message, $message_args));
    }
  }

}
