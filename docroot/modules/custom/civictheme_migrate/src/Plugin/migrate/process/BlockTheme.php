<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\Core\Config\Config;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Migrate process plugin to get default theme.
 *
 * @MigrateProcessPlugin(
 *   id = "ct_block_theme"
 * )
 */
class BlockTheme extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Contains the configuration object factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Contains the system.theme configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $themeConfig;

  /**
   * Constructs a BlockTheme object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *   The migration entity.
   * @param \Drupal\Core\Config\Config $theme_config
   *   The system.theme configuration factory object.
   * @param array $themes
   *   The list of themes available on the destination.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, Config $theme_config, array $themes) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    $this->themeConfig = $theme_config;
    $this->themes = $themes;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('config.factory')->get('system.theme'),
      $container->get('theme_handler')->listInfo()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Get default theme.
    return $this->themeConfig->get('default');
  }

}
