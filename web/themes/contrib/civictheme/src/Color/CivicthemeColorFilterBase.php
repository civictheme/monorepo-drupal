<?php

declare(strict_types=1);

namespace Drupal\civictheme\Color;

/**
 * Abstract color filter.
 *
 * Color filters allows to modify colors.
 */
abstract class CivicthemeColorFilterBase {

  /**
   * An array of argument values the filter accepts.
   *
   * @var array<mixed>
   */
  protected $arguments;

  /**
   * Constructor.
   *
   * @param array $arguments
   *   Filter arguments.
   */
  public function __construct(array $arguments = []) {
    $this->setArguments($arguments);
  }

  /**
   * Filter name.
   */
  abstract public static function name(): string;

  /**
   * Apply a filter to a color.
   *
   * @param string $color
   *   Color to apply the filter.
   *
   * @return string
   *   A color with an applied filter.
   */
  abstract public function filter(string $color): string;

  /**
   * Number of expected arguments.
   *
   * @return int
   *   Number of expected arguments.
   */
  abstract protected function expectedArgumentsCount(): int;

  /**
   * Set filter arguments.
   *
   * @SuppressWarnings(MissingImport)
   */
  public function setArguments(array $arguments): void {
    if (count($arguments) != $this->expectedArgumentsCount()) {
      throw new \Exception(sprintf('Invalid number of arguments passed to the color filter: passed %s but expedted %s.', count($arguments), $this->expectedArgumentsCount()));
    }

    $this->arguments = $arguments;
  }

  /**
   * Create a filter from the definition.
   *
   * @param string $name
   *   Filter name.
   * @param array $arguments
   *   An array of filter arguments.
   *
   * @return \Drupal\civictheme\Color\CivicthemeColorFilterBase|null
   *   An instance of the filter class or NULL if filter with provided name
   *   was not found.
   */
  public static function fromDefinition(string $name, array $arguments): ?CivicthemeColorFilterBase {
    foreach (get_declared_classes() as $class) {
      if (is_subclass_of($class, static::class) && $class::name() === $name) {
        return new $class($arguments);
      }
    }

    return NULL;
  }

}
