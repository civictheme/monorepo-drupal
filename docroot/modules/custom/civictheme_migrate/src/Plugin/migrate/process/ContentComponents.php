<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\civictheme_migrate\ContentComponentFactory;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigratePluginManager;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\paragraphs\ParagraphInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Content Components creator.
 *
 * @MigrateProcessPlugin(
 *   id = "ct_content_component_create",
 *   handle_multiples = TRUE
 * )
 *
 * @package Drupal\civictheme_migrate\Plugin\migrate\process
 */
class ContentComponents extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Content Component Factory.
   *
   * @var \Drupal\civictheme_migrate\ContentComponentFactory
   */
  protected $factory;

  /**
   * The MigratePluginManager instance.
   *
   * @var \Drupal\migrate\Plugin\MigratePluginManagerInterface
   */
  protected $migratePluginManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ContentComponentFactory $factory, MigratePluginManager $migrate_plugin_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->factory = $factory;
    $this->migratePluginManager = $migrate_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('civictheme_migrate.migration.content_component_factory'),
      $container->get('plugin.manager.migrate.process')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!is_array($value)) {
      return NULL;
    }

    $items = [];
    $context = [
      'configuration' => $this->configuration,
      'migration' => $migrate_executable,
      'row' => clone $row,
      'destination_property' => $destination_property,
    ];

    $paragraphs = $this->factory->createComponents($value, $context);
    if ($paragraphs) {
      foreach ($paragraphs as $paragraph) {
        if ($paragraph instanceof ParagraphInterface) {
          $items[] = $paragraph;
        }
      }
    }

    return $items;
  }

  /**
   * Transform a destination value.
   *
   * @param mixed $value
   *   The value to transform.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The current migration.
   * @param \Drupal\migrate\Row $row
   *   The current row.
   *
   * @return mixed
   *   The transformed value.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  protected function getTransformedValue($value, MigrateExecutableInterface $migrate_executable, Row $row) {
    if (is_array($value)) {
      $plugin = $this->migratePluginManager->createInstance($value['plugin'], $value);
    }
    else {
      $plugin = $this->migratePluginManager->createInstance('get', ['source' => $value]);
    }
    return $plugin->transform(NULL, $migrate_executable, $row, $value);
  }

}
