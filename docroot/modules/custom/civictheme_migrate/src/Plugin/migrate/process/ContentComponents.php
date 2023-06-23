<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\civictheme_migrate\Component\ComponentFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Migrate plugin used to process content components.
 *
 * @MigrateProcessPlugin(
 *   id = "content_components",
 *   handle_multiples = TRUE
 * )
 *
 * @code
 * process:
 *   destination_field:
 *     plugin: content_components
 *     source: source_field
 * @endcode
 */
class ContentComponents extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Content Component Factory.
   *
   * @var Drupal\civictheme_migrate\Component\ComponentFactory
   */
  protected $componentFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ComponentFactory $component_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->componentFactory = $component_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('civictheme_migrate.component_factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $components = $this->componentFactory->createComponents($value, [
      'configuration' => $this->configuration,
      'migration' => $migrate_executable,
      'row' => clone $row,
      'destination_property' => $destination_property,
    ]);

    foreach ($this->componentFactory->getMessages() as $message) {
      $migrate_executable->saveMessage($message, MigrationInterface::MESSAGE_INFORMATIONAL);
    }

    return $components;
  }

}
