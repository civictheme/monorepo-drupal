<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Migrate process plugin used to set 'external' link property value.
 *
 * @MigrateProcessPlugin(
 *   id = "link_is_external"
 * )
 *
 * To do custom value transformations use the following:
 *
 * @code
 * plugin:
 *   plugin: link_is_external
 *   source: name
 * @endcode
 */
class LinkIsExternal extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
    );
  }

  /**
   * {@inheritdoc}
   *
   * Set the block plugin id.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return (int) (UrlHelper::isValid($value) && UrlHelper::isExternal($value));
  }

}
