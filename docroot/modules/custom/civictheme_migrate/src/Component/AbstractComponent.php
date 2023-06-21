<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\migrate\MigrateLookupInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * Abstract Component.
 *
 * Components represent Drupal components and are named after machine names.
 *
 * Components are built using composition (via traits) to reduce code
 * duplication of processors.
 *
 * The component structure represents Drupal entities used for this component.
 *
 * @package Drupal\civictheme_migrate
 */
abstract class AbstractComponent implements ComponentInterface, MigratableComponentInterface {

  /**
   * Internal structure of the component populated by build() method.
   *
   * @var mixed
   */
  protected $structure;

  /**
   * Entity Type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The migrate lookup service.
   *
   * @var \Drupal\migrate\MigrateLookupInterface
   */
  protected $migrateLookup;

  /**
   * AbstractComponent constructor.
   *
   * @param mixed $data
   *   The data from the source mapping.
   * @param array $context
   *   Migration context.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\migrate\MigrateLookupInterface $migrate_lookup
   *   The migrate lookup service.
   */
  public function __construct($data, array &$context, EntityTypeManagerInterface $entity_type_manager, MigrateLookupInterface $migrate_lookup) {
    $this->entityTypeManager = $entity_type_manager;
    $this->migrateLookup = $migrate_lookup;

    // Validate data.
    $this->validateMigrateData($data);
    // Prepare stub from the provided data.
    $stub = $this->prepareStub($data, $context);
    // Convert data to fields.
    $this->fieldsFromStub($stub);
    // Build structure.
    $this->structure = $this->build();
  }

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return (new CamelCaseToSnakeCaseNameConverter())
      ->normalize((new \ReflectionClass(static::class))->getShortName());
  }

  /**
   * {@inheritdoc}
   */
  public static function migrateName(): string {
    // By default, the name of the source mapping field is the name of the
    // destination component class.
    return static::name();
  }

  /**
   * {@inheritdoc}
   */
  public function structure(): mixed {
    return $this->structure;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray(): array {
    $stub = [];

    $reflection = new \ReflectionClass($this);
    foreach ($reflection->getMethods() as $method) {
      if (str_starts_with($method->getName(), 'get') && $method->getDeclaringClass()->getName() === $reflection->getName()) {
        $field_name = (new CamelCaseToSnakeCaseNameConverter())
          ->normalize(substr($method->getName(), 3));
        $stub[$field_name] = $method->invoke($this);
      }
    }

    return $stub;
  }

  /**
   * Validate the migrate data required to create the component.
   *
   * @param array $data
   *   The data from the source mapping.
   *
   * @throws \Exception
   *   If the data is not valid.
   */
  protected function validateMigrateData(array $data): void {
    // Validate that all required fields are present.
    // Other validations may be added in the implementation classes.
    $fields = static::migrateFields();
    $missing_fields = array_keys(array_diff_key(array_flip($fields), $data));
    if (!empty($missing_fields)) {
      throw new \Exception(sprintf('Missing fields: %s', implode(', ', $missing_fields)));
    }
  }

  /**
   * Prepare data before creating the component.
   *
   * Implementations may override this method to prepare data before creating
   * components.
   *
   * @param mixed $data
   *   The data from the source mapping which has already been validated.
   * @param array $context
   *   The migration context.
   *
   * @return array
   *   The data to then pass to the component for creation.
   */
  protected function prepareStub($data, array $context): array {
    return $data;
  }

  /**
   * Convert stub data to fields using setters.
   *
   * This guarantees that the component will have only supported fields.
   *
   * @param array $stub
   *   The stub data.
   */
  protected function fieldsFromStub(array $stub): void {
    foreach ($stub as $field_name => $value) {
      $method = 'set' . (new CamelCaseToSnakeCaseNameConverter(NULL, TRUE))->denormalize($field_name);
      if (method_exists(static::class, $method)) {
        $this->{$method}($value);
      }
    }
  }

  /**
   * Build a component using provided data.
   *
   * The component name and data has already been validated and normalised
   * prior to calling this method.
   *
   * Implementations can return any type of object expected by the calling
   * factory. They can also throw an exception if the component cannot be
   * instantiated.
   *
   * @return mixed
   *   Component built structure.
   */
  abstract protected function build(): mixed;

}
