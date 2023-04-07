<?php

namespace Drupal\civictheme_migrate;

/**
 * Manage migration schemas.
 */
interface MigrationSchemaManagerInterface {

  /**
   * Get all migration schemas.
   *
   * @return \Drupal\civictheme_migrate\MigrationSchema[]
   *   Array of migration schemas.
   */
  public function getSchemas(): array;

  /**
   * Get a migration schema by id.
   *
   * @param string $id
   *   The migration schema ID.
   *
   * @return \Drupal\civictheme_migrate\MigrationSchema|null
   *   Migration schema of found or NULL otherwise.
   */
  public function getSchema(string $id): MigrationSchema|null;

}
