<?php

namespace Drupal\civictheme_migrate;

use Drupal\Core\Extension\ExtensionPathResolver;
use JakubOnderka\PhpParallelLint\ErrorFormatter;
use Opis\JsonSchema\Validator;

/**
 * Validates uploaded migration source JSON files to confirm with schema.
 */
class CivicThemeMigrateValidator {
  /**
   * Extension path resolver instance.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected ExtensionPathResolver $extensionPathResolver;


  /**
   * JsonScheme Validator.
   *
   * @var \Opis\JsonSchema\Validator
   */
  protected Validator $validator;

  /**
   * Constructor.
   *
   * @param \Opis\JsonSchema\Validator $validator
   *   JSON Schema Validator.
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   Service to resolve extension paths.
   */
  public function __construct(Validator $validator, ExtensionPathResolver $extension_path_resolver) {
    $this->validator = $validator;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->registerSchemas();
  }

  /**
   * Helper to register schemas required for validating extracted content JSON.
   *
   * Create schema files in `assets/schema` directory, the filename without
   * file extension is the identifier for the schema being registered.
   */
  protected function registerSchemas() {
    $schema_directory = $this->extensionPathResolver->getPath('module', 'civictheme_migrate') . '/assets/schema/*.json';
    $files = glob($schema_directory);
    foreach ($files as $file) {
      $schema_id = str_replace('.json', '', basename($file));
      $this->validator->resolver()->registerFile($this->getSchemeUrl($schema_id), DRUPAL_ROOT . '/' . $file);
    }
  }

  /**
   * Validate the JSON data against specified schema.
   *
   * @param mixed $data
   *   Data to validate against.
   * @param string $scheme_id
   *   Scheme id to validate data against.
   *
   * @return array
   *   Array of error messages from validation.
   */
  public function validate($data, $scheme_id):array {
    $errors = [];

    if ($data === NULL) {
      return ['JSON is malformed / invalid.'];
    }

    $validation_result = $this->validator->validate($data, $this->getSchemeUrl($scheme_id));
    $formatter = new ErrorFormatter();
    $errors = [$formatter->format($validation_result->error())];

    return $errors;
  }

  /**
   * Generates a URI compliant schema id.
   *
   * The package requires schema id to be in URI format.
   *
   * @param string $schema_id
   *   Human-readable scheme ID.
   *
   * @return string
   *   URI scheme id.
   */
  protected function getSchemeUrl(string $schema_id):string {
    return 'https://civictheme.io/api/migration/' . $schema_id;
  }

}
