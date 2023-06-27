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

  /**
   * Load classes from path.
   *
   * @param string $path
   *   Parent class name.
   * @param string $parent_class
   *   Lookup path.
   *
   * @return array
   *   Array of loaded class instances.
   */
  public static function loadClasses(string $path, $parent_class = NULL): array {
    $classes = [];

    if (!empty($path) && is_dir($path)) {
      foreach (glob($path . '/*.php') as $filename) {
        if ($filename !== __FILE__ && !str_contains(basename($filename), 'Trait')) {
          require_once $filename;
        }
      }
    }

    if ($parent_class) {
      foreach (get_declared_classes() as $class) {
        if (is_subclass_of($class, $parent_class) && !(new \ReflectionClass($class))->isAbstract()) {
          $classes[] = $class;
        }
      }
    }

    return $classes;
  }

  /**
   * Extract array value by path.
   *
   * @param mixed $data
   *   Data to extract value from.
   * @param string $path
   *   Path to the data as a forward-slash separated string of keys with * as
   *   a wildcard.
   *
   * @return mixed
   *   The extracted value.
   */
  public static function extractValueByPath(mixed $data, string $path): mixed {
    $segments = explode('/', $path);

    if (count(array_filter($segments)) === 0) {
      return $data;
    }

    $segment = array_shift($segments);

    if ($segment === '*') {
      $result = '';
      foreach ($data as $item) {
        $result = static::extractValueByPath($item, implode('/', $segments));
        if (!empty($result)) {
          break;
        }
      }

      return $result;
    }

    if (is_array($data) && !array_key_exists($segment, $data)) {
      return '';
    }

    return is_array($data) ? static::extractValueByPath($data[$segment], implode('/', $segments)) : '';
  }

}
