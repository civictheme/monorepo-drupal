<?php

namespace Drupal\civictheme_migrate\Validator;

/**
 * Validates migration file against predefined migration schema.
 */
interface MigrationFileValidatorInterface {

  /**
   * Validates file against the specified schema and generates a list of errors.
   *
   * @param string $migration_schema_id
   *   Migration schema ID to use for validation.
   * @param string $filepath
   *   The file path to a file to validate.
   *
   * @return array
   *   Array of error messages returned from the validation.
   */
  public function validate(string $migration_schema_id, string $filepath): array;

}
