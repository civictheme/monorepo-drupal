<?php

namespace Drupal\civictheme_migrate;

/**
 * Migration schema.
 *
 * Used to work with migration schemas represented as files.
 */
class MigrationSchema {

  /**
   * The migration schema ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The migration schema file path.
   *
   * @var string
   */
  protected $filepath;

  /**
   * The migration schema data.
   *
   * @var mixed
   */
  protected $data;

  /**
   * Instantiate an instance from provided migration schema file.
   *
   * @param string $filepath
   *   The file path to read the migration schema data from.
   *
   * @return \Drupal\civictheme_migrate\MigrationSchema
   *   The instantiated migration schema.
   */
  public static function fromFilepath(string $filepath): MigrationSchema {
    $instance = new static();
    $instance->setFilepath($filepath);
    $instance->setId(static::idFromFilepath($instance->getFilepath()));
    $instance->setData(static::dataFromFile($instance->getFilepath()));

    return $instance;
  }

  /**
   * Get the migration schema ID.
   *
   * @return string
   *   The migration schema ID.
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * Set the migration schema ID.
   *
   * @param string $id
   *   The migration schema ID.
   *
   * @return MigrationSchema
   *   Current instance for used for chaining.
   */
  public function setId(string $id): MigrationSchema {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the migration schema file path.
   *
   * @return string
   *   The migration schema file path.
   */
  public function getFilepath(): string {
    return $this->filepath;
  }

  /**
   * Set the migration schema file path.
   *
   * @param string $filepath
   *   The file path to set.
   *
   * @return MigrationSchema
   *   Current instance for used for chaining.
   */
  public function setFilepath(string $filepath): MigrationSchema {
    Utility::validateFileExists($filepath);
    $this->filepath = $filepath;

    return $this;
  }

  /**
   * Get the migration schema data.
   *
   * @return mixed
   *   The migration schema data.
   */
  public function getData(): mixed {
    return $this->data;
  }

  /**
   * Set the migration schema data.
   *
   * @param mixed $data
   *   The migration schema data to set.
   *
   * @return MigrationSchema
   *   Current instance for used for chaining.
   */
  public function setData(mixed $data): MigrationSchema {
    $this->data = $data;

    return $this;
  }

  /**
   * Get the migration schema URI.
   *
   * @return string
   *   The migration schema URI.
   */
  public function getUri(): string {
    return 'https://www.civictheme.io/api/migration/' . $this->id;
  }

  /**
   * Get formatted data.
   *
   * @return string
   *   Formatted data as a string.
   */
  public function formatData(): string {
    return json_encode($this->data, JSON_PRETTY_PRINT);
  }

  /**
   * Create an ID from the provided entity type and bundle.
   *
   * @param string $entity_type_id
   *   The entity type.
   * @param string $bundle
   *   The entity bundle.
   *
   * @return string
   *   The migration schema ID.
   */
  public static function idFromEntityTypeBundle(string $entity_type_id, string $bundle): string {
    return $entity_type_id . '_' . $bundle;
  }

  /**
   * Create an ID from the provided file path.
   *
   * @param string $filepath
   *   The migration schema file path.
   *
   * @return string
   *   The migration schema ID.
   */
  protected static function idFromFilepath(string $filepath): string {
    Utility::validateFileExists($filepath);

    return basename($filepath, '.json');
  }

  /**
   * Extract data from provided file.
   *
   * @param string $filepath
   *   The migration schema file path.
   *
   * @return mixed
   *   The migration schema
   */
  protected static function dataFromFile(string $filepath): mixed {
    Utility::validateFileExists($filepath);

    return json_decode(file_get_contents($filepath));
  }

}
