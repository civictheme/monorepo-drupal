<?php

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
   * @var array
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
  abstract public static function name();

  /**
   * Apply a filter to a color.
   *
   * @param string $color
   *   Color to apply the filter.
   *
   * @return string
   *   A color with an applied filter.
   */
  abstract public function filter($color);

  /**
   * Number of expected arguments.
   *
   * @return int
   *   Number of expected arguments.
   */
  abstract protected function expectedArgumentsCount();

  /**
   * Set filter arguments.
   *
   * @SuppressWarnings(MissingImport)
   */
  public function setArguments(array $arguments) {
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
   * @return mixed|null
   *   An instance of the filter class or NULL if filter with provided name
   *   was not found.
   */
  public static function fromDefinition($name, array $arguments) {
    foreach (get_declared_classes() as $class) {
      if (is_subclass_of($class, static::class) && $class::name() === $name) {
        return new $class($arguments);
      }
    }
    return NULL;
  }

}
