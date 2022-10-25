<?php

namespace Drupal\civictheme_migrate;

use Drupal\Core\Extension\ExtensionPathResolver;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator;

/**
 * Validates uploaded migration source JSON files to confirm with schema.
 */
class CivicthemeMigrateValidator {
  /**
   * Extension path resolver instance.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected $extensionPathResolver;


  /**
   * JsonScheme Validator.
   *
   * @var \Opis\JsonSchema\Validator
   */
  protected $jsonValidator;

  /**
   * Constructor.
   *
   * @param \Opis\JsonSchema\Validator $json_validator
   *   JSON Schema Validator.
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   Service to resolve extension paths.
   */
  public function __construct(Validator $json_validator, ExtensionPathResolver $extension_path_resolver) {
    $this->jsonValidator = $json_validator;
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
    $schema_directory = $this->extensionPathResolver->getPath('module', 'civictheme_migrate') . '/assets/schema/';
    $files = glob($schema_directory . '*.json');
    foreach ($files as $file) {
      $schema_id = basename($file, '.json');
      $this->jsonValidator->resolver()->registerFile($this->getSchemeUrl($schema_id), DRUPAL_ROOT . '/' . $file);
    }
  }

  /**
   * Validate JSON data against specified schema and generate list of errors.
   *
   * If the array is empty then the JSON is valid against the schema.
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

    $validation_result = $this->jsonValidator->validate($data, $this->getSchemeUrl($scheme_id));
    if ($validation_result->hasError()) {
      $formatter = new ErrorFormatter();
      $formatted_errors = $formatter->formatFlat($validation_result->error());
      $errors = $formatted_errors;
    }
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
