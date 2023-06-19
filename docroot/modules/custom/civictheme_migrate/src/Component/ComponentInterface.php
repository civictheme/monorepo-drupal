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
  public static function getName(): string;

  /**
   * The name of the source component in the source mapping.
   *
   * The component may have another name in the source mapping.
   *
   * @return string
   *   The name of the source component in the source mapping.
   */
  public static function getSrcName(): string;

  /**
   * The fields that the source component must have.
   *
   * @return array
   *   The fields that the source component must have.
   */
  public static function getSrcFields(): array;

  /**
   * Component structure representation.
   *
   * @return mixed
   *   Component structure representation.
   */
  public function getStructure(): mixed;

}
