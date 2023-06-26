<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Converter\ConverterManager;
use Drupal\civictheme_migrate\Utility;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\migrate\MigrateLookupInterface;

/**
 * Content Component Factory.
 *
 * @package Drupal\civictheme_migrate
 */
class ComponentFactory {

  /**
   * ContentComponentFactory constructor.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected MigrateLookupInterface $migrateLookup,
    protected ConverterManager $converterManager) {
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
          $this->messages[] = sprintf('Skipped incorrectly instantiated component "%s".', $name);
          continue;
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

    if (!empty($context['configuration']['excluded_converters'])) {
      $this->converterManager->setExcludedConverters($context['configuration']['excluded_converters']);
    }

    if (!empty($context['configuration']['local_domains'])) {
      $this->converterManager->setLocalDomains($context['configuration']['local_domains']);
    }

    /** @var \Drupal\civictheme_migrate\Component\ComponentInterface $component */
    $component = new $component_class($data, $context, $this->entityTypeManager, $this->migrateLookup, $this->converterManager);

    return $component->structure();
  }

  /**
   * Get messages populated during component creation.
   *
   * @return array
   *   The list of messages.
   */
  public function getMessages() {
    // Collect messages from converters only.
    return $this->converterManager->getMessages();
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
    $component_classes = Utility::loadClasses(__DIR__, AbstractComponent::class);

    foreach ($component_classes as $component_class) {
      if ($component_class::migrateName() == $migrate_name) {
        return $component_class;
      }
    }

    return NULL;
  }

}
