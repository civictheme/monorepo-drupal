<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\migrate\MigrateLookupInterface;

/**
 * Content Component Factory.
 *
 * @package Drupal\civictheme_migrate
 */
class ComponentFactory {

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
   * ContentComponentFactory constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\migrate\MigrateLookupInterface $migrate_lookup
   *   The migrate lookup service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MigrateLookupInterface $migrate_lookup) {
    $this->entityTypeManager = $entity_type_manager;
    $this->migrateLookup = $migrate_lookup;
  }

  /**
   * Create components.
   *
   * @param mixed $items
   *   The list of items to create.
   * @param array $context
   *   The migration context array with 4 keys:
   *    - configuration: the configuration of the current field.
   *    - migration: the current migrate executable.
   *    - row: the current row.
   *    - destination_property: destination property currently worked on.
   *
   * @return array|null
   *   Array of created components instances or NULL if the value was not an
   *   array.
   */
  public function createComponents($items, array $context): array|null {
    if (!is_array($items)) {
      return NULL;
    }

    $components = [];
    foreach ($items as $item) {
      foreach ($item as $name => $data) {
        try {
          $component = $this->createComponent($name, $data, $context);
        }
        catch (\Exception $e) {
          continue;
          // Skip incorrectly instantiated components.
          // @todo Add logging.
        }
        $components[] = $component;
      }
    }

    return $components;
  }

  /**
   * Create a single component.
   *
   * @param string $migrate_name
   *   The source name of the component to create.
   * @param mixed $data
   *   The item data to create the component.
   * @param array $context
   *   The migration context.
   *
   * @return mixed|null
   *   The component.
   */
  public function createComponent(string $migrate_name, $data, array &$context) {
    $component_class = $this->findComponentClassByMigrateName($migrate_name);

    if (!$component_class || !class_exists($component_class)) {
      throw new \Exception(sprintf('Component type %s not found.', $migrate_name));
    }

    /** @var \Drupal\civictheme_migrate\Component\ComponentInterface $component */
    $component = new $component_class($data, $context, $this->entityTypeManager, $this->migrateLookup);

    return $component->structure();
  }

  /**
   * Find the component class by the source name.
   *
   * @param string $migrate_name
   *   The migrate name of the component.
   *
   * @return string|null
   *   The component class name or NULL if the class fo the specified source
   *   name cannot be found.
   */
  protected function findComponentClassByMigrateName(string $migrate_name): string|null {
    $component_classes = $this->getComponentClasses(__DIR__, '\Drupal\civictheme_migrate\Component\AbstractComponent');

    foreach ($component_classes as $component_class) {
      if ($component_class::migrateName() == $migrate_name) {
        return $component_class;
      }
    }

    return NULL;
  }

  /**
   * Load component classes.
   *
   * @param string $path
   *   Parent class name.
   * @param string $parent_class
   *   Lookup path.
   *
   * @return array
   *   Array of loaded class instances.
   */
  protected function getComponentClasses($path, $parent_class = NULL) {
    foreach (glob($path . '/*.php') as $filename) {
      if ($filename !== __FILE__ && !str_contains(basename($filename), 'Trait')) {
        require_once $filename;
      }
    }

    $children = [];

    if ($parent_class) {
      foreach (get_declared_classes() as $class) {
        if (is_subclass_of($class, $parent_class) && !(new \ReflectionClass($class))->isAbstract()) {
          $children[] = $class;
        }
      }
    }

    return $children;
  }

}
