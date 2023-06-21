<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Interface ComponentInterface.
 *
 * Represents a component.
 */
interface ComponentInterface {

  /**
   * The name of the component.
   *
   * Normally, defaults to the snake-cased name of the class.
   *
   * @return string
   *   The name of the component.
   */
  public static function name(): string;

  /**
   * Represent component fields as an array.
   *
   * @return array
   *   Component fields as an array.
   */
  public function toArray(): array;

  /**
   * Component structure.
   *
   * @return mixed
   *   Component structure representation.
   */
  public function structure(): mixed;

}
