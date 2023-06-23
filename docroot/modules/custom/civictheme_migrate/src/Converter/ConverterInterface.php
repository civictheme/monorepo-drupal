<?php

namespace Drupal\civictheme_migrate\Converter;

/**
 * Interface ConverterInterface.
 *
 * Defines the interface for a generic converter.
 */
interface ConverterInterface {

  /**
   * Converter name.
   *
   * @return string
   *   The converter name.
   */
  public static function name(): string;

  /**
   * Converter weight.
   *
   * @return int
   *   The converter weight. Converters will run in order of weight with the
   *   lowest weight running first.
   */
  public static function weight(): int;

  /**
   * Convert a value.
   *
   * @param mixed $value
   *   The value to convert.
   *
   * @return mixed
   *   The converted value.
   */
  public function convert(mixed $value): mixed;

}
