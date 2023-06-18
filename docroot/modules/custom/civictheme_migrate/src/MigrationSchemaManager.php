<?php

namespace Drupal\civictheme_migrate;

use Drupal\Core\Extension\ExtensionPathResolver;

/**
 * Manage migration schemas.
 */
class MigrationSchemaManager implements MigrationSchemaManagerInterface {

  /**
   * Defines default schemas directory.
   */
  const SCHEMAS_DIRECTORY = 'schemas';

  /**
   * Discovered schemas.
   *
   * @var \Drupal\civictheme_migrate\MigrationSchema[]
   */
  protected $schemas = [];

  /**
   * The schemas directory to use for discovery.
   *
   * @var string
   */
  protected $schemasDirectory;

  /**
   * Extension path resolver instance.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected $extensionPathResolver;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   Service to resolve extension paths.
   */
  public function __construct(ExtensionPathResolver $extension_path_resolver) {
    $this->extensionPathResolver = $extension_path_resolver;
    $this->setDirectory($this->extensionPathResolver->getPath('module', 'civictheme_migrate') . DIRECTORY_SEPARATOR . self::SCHEMAS_DIRECTORY);
    $this->schemas = $this->discoverSchemas();
  }

  /**
   * {@inheritdoc}
   */
  public function getSchemas(): array {
    return $this->schemas;
  }

  /**
   * {@inheritdoc}
   */
  public function getSchema(string $id): MigrationSchema|null {
    foreach ($this->schemas as $schema) {
      if ($schema->getId() == $id) {
        return $schema;
      }
    }

    return NULL;
  }

  /**
   * Get migration schema directory path.
   *
   * @return string
   *   The migration schema directory path.
   */
  public function getDirectory(): string {
    return $this->schemasDirectory;
  }

  /**
   * Set migration schema directory path.
   *
   * @param string $directory
   *   The migration schema directory path.
   */
  public function setDirectory(string $directory): void {
    $this->schemasDirectory = rtrim($directory, DIRECTORY_SEPARATOR);
  }

  /**
   * Discover migration schemas within schemasDirectory.
   *
   * @return \Drupal\civictheme_migrate\MigrationSchema[]
   *   Array of discovered MigrationSchema instances.
   */
  protected function discoverSchemas(): array {
    $schemas = [];

    $files = glob($this->schemasDirectory . DIRECTORY_SEPARATOR . '*.json');
    foreach ($files as $file) {
      try {
        $schemas[] = MigrationSchema::fromFilepath($file);
      }
      catch (\Exception $exception) {
        // Skip incorrectly formatted schemas.
        continue;
      }
    }

    return $schemas;
  }

}
