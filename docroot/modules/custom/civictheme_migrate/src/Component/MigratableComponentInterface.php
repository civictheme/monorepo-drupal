<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Interface MigratableComponentInterface.
 *
 * Provides migratable capabilities to components.
 */
interface MigratableComponentInterface {

  /**
   * The name of the migrate component in the source mapping.
   *
   * Allows component to have another name in the source mapping.
   *
   * @return string
   *   The name of the source component in the source mapping.
   */
  public static function migrateName(): string;

  /**
   * The fields that the source component must have.
   *
   * @return array
   *   The fields that the source component must have.
   */
  public static function migrateFields(): array;

}
