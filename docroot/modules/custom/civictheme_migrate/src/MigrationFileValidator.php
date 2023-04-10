<?php

namespace Drupal\civictheme_migrate;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator;

/**
 * Validates uploaded migration source JSON files to confirm with schema.
 */
class MigrationFileValidator implements MigrationFileValidatorInterface {

  use StringTranslationTrait;

  /**
   * The migration schema manager.
   *
   * @var \Drupal\civictheme_migrate\MigrationSchemaManagerInterface
   */
  protected $migrationSchemaManager;

  /**
   * Schema validator.
   *
   * @var \Opis\JsonSchema\Validator
   *
   * @note Currently, this validator is used directly for simplicity. However,
   * it can be defined as a plugin in the future for more flexibility.
   */
  protected $schemaValidator;

  /**
   * Constructor.
   *
   * @param \Drupal\civictheme_migrate\MigrationSchemaManagerInterface $migration_schema_manager
   *   The migration schema manager.
   * @param \Opis\JsonSchema\Validator $validator
   *   The schema validator service.
   */
  public function __construct(MigrationSchemaManagerInterface $migration_schema_manager, Validator $validator) {
    $this->migrationSchemaManager = $migration_schema_manager;
    $this->schemaValidator = $validator;
    $this->registerMigrationSchemas();
  }

  /**
   * {@inheritdoc}
   */
  public function validate(string $migration_schema_id, string $filepath): array {
    $migration_schema = $this->migrationSchemaManager->getSchema($migration_schema_id);
    if (!$migration_schema) {
      throw new \RuntimeException(sprintf('Unable to load schema "%s".', $migration_schema->getId()));
    }

    Utility::validateFileExists($filepath);

    $data = $this->prepareFileData(file_get_contents($filepath));

    $errors = [];

    if (empty($data)) {
      $errors[] = $this->t('No data provided.');
    }

    $result = $this->schemaValidator->validate($data, $migration_schema->getUri());
    if ($result->hasError()) {
      $formatter = new ErrorFormatter();
      $errors = $formatter->formatFlat($result->error());
    }

    return $errors;
  }

  /**
   * Register schemas with the validator.
   */
  protected function registerMigrationSchemas(): void {
    $schemas = $this->migrationSchemaManager->getSchemas();
    foreach ($schemas as $schema) {
      $this->schemaValidator->resolver()->registerFile($schema->getUri(), $schema->getFilepath());
    }
  }

  /**
   * Prepare file data before passing to validation.
   *
   * @param mixed $data
   *   The data to prepare.
   *
   * @return mixed
   *   Processed data.
   */
  protected function prepareFileData(mixed $data): mixed {
    return json_decode($data);
  }

}
