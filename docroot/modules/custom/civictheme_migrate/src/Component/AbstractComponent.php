<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\migrate\MigrateLookupInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * Abstract Component.
 *
 * @package Drupal\civictheme_migrate
 */
abstract class AbstractComponent implements ComponentInterface {

  /**
   * Component structure representation.
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

    $this->validateData($data);

    $data = $this->prepareData($data, $context);

    $this->structure = $this->build(static::getName(), $data);
  }

  /**
   * {@inheritdoc}
   */
  public static function getName(): string {
    return (new CamelCaseToSnakeCaseNameConverter())
      ->normalize((new \ReflectionClass(static::class))->getShortName());
  }

  /**
   * {@inheritdoc}
   */
  public static function getSrcName(): string {
    // By default, the name of the source mapping field is the name of the
    // destination component class.
    return static::getName();
  }

  /**
   * {@inheritdoc}
   */
  public function getStructure(): mixed {
    return $this->structure;
  }

  /**
   * Validate the data required to create the component.
   *
   * @param array $data
   *   The data from the source mapping.
   *
   * @throws \Exception
   *   If the data is not valid.
   */
  protected function validateData(array $data): void {
    // Validate that all required fields are present.
    // Other validations may be added in the implementation classes.
    $fields = static::getSrcFields();
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
  protected function prepareData($data, array $context): array {
    return [];
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
   * @param string $name
   *   The name of the component.
   * @param array $data
   *   The data to pass to the component.
   *
   * @return mixed
   *   Component instance.
   */
  abstract protected function build(string $name, array $data): mixed;

}
