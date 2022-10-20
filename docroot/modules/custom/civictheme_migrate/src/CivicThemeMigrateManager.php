<?php

namespace Drupal\civictheme_migrate;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\File\FileSystemInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Manage and configure CivicTheme Migrations.
 */
class CivicThemeMigrateManager {
  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The file storage service.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $fileStorage;

  /**
   * Yaml serializer.
   *
   * @var \Drupal\Component\Serialization\Yaml
   */
  protected Yaml $yaml;

  /**
   * Extemnsion Path resolver.
   *
   * @var \Drupal\civictheme_migrate\Form\ExtensionPathResolver
   */
  protected ExtensionPathResolver $extensionPathResolver;

  /**
   * Config Importer.
   *
   * @var \Drupal\Core\Config\ConfigImporter
   */
  protected ConfigImporter $configImporter;

  /**
   * Migration Entity Storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $migrationStorage;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $client, FileSystemInterface $file_system, EntityStorageInterface $file_storage, Yaml $yaml_serializer, ExtensionPathResolver $extension_path_resolver, EntityStorageInterface $migration_storage) {
    $this->httpClient = $client;
    $this->fileSystem = $file_system;
    $this->fileStorage = $file_storage;
    $this->yaml = $yaml_serializer;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->migrationStorage = $migration_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $container->get('config.factory'),
      $container->get('http_client'),
      $container->get('file_system'),
      $entity_type_manager->getStorage('file'),
      $container->get('serialization.yaml'),
      $container->get('extension.path.resolver'),
      $entity_type_manager->getStorage('migration'),
    );
  }

}
