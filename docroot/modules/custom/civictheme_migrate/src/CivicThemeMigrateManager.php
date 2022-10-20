<?php

namespace Drupal\civictheme_migrate;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use GuzzleHttp\ClientInterface;
use PHPUnit\Util\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Manage and configure CivicTheme Migrations.
 */
class CivicThemeMigrateManager implements ContainerInjectionInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * CivicTheme Migrate config settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

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
   * @var \Drupal\Core\Extension\ExtensionPathResolver
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
   * Messenger instance.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $client, FileSystemInterface $file_system, Yaml $yaml_serializer, ExtensionPathResolver $extension_path_resolver, MessengerInterface $messenger, EntityTypeManagerInterface $entity_type_manager) {
    $this->config = $config_factory->getEditable('civictheme_migrate.settings');
    $this->httpClient = $client;
    $this->fileSystem = $file_system;
    $this->fileStorage = $entity_type_manager->getStorage('file');
    $this->yaml = $yaml_serializer;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->migrationStorage = $entity_type_manager->getStorage('migration');
    $this->setMessenger($messenger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('http_client'),
      $container->get('file_system'),
      $container->get('serialization.yaml'),
      $container->get('extension.path.resolver'),
      $container->get('messenger'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Generates or updates an existing migration for CivicTheme.
   *
   * @param array $file_ids
   *   List of source migration file ids to add to migration.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function generateMigration(array $file_ids) {
    $files = $this->fileStorage->loadMultiple($file_ids);
    if (empty($files)) {
      $this->messenger()->addStatus($this->t('Require files to generate a migration'));
    }
    $file_stream_wrappers = [];
    foreach ($files as $file) {
      $file_stream_wrappers[] = $file->getFileUri();
    }
    $migration_config_file_name = $this->extensionPathResolver->getPath('module', 'civictheme_migrate') . '/assets/migrations/migrate_plus.migration.civictheme_page.yml';
    $migration_config = file_get_contents($migration_config_file_name);
    $migration_config = $this->yaml->decode($migration_config);
    $migration_config['source']['urls'] = $file_stream_wrappers;
    $existing_migration = $this->migrationStorage->load('civictheme_page_migrate');
    if ($existing_migration === NULL) {
      $migration = $this->migrationStorage->create($migration_config);
      $migration->save();
      $this->messenger()->addStatus($this->t('Migration has been generated.'));
    }
    else {
      // Adding all keys in case we update the asset migration template.
      $existing_migration->set('process', $migration_config['process']);
      $existing_migration->set('source', $migration_config['source']);
      $existing_migration->set('destination', $migration_config['destination']);
      $existing_migration->save();
      $this->messenger()->addStatus($this->t('Migration has been updated.'));
    }
  }

  /**
   * Retrieve a remote migration JSON configuration file and store locally.
   *
   * @param array $urls
   *   Array of remote migration files.
   * @param array $validators
   *   File upload validators.
   *
   * @return array
   *   Array of file ids.
   *
   * @throws \Exception
   */
  public function retrieveRemoteFiles(array $urls, array $validators):array {
    $files = [];
    $auth_headers = $this->getAuthHeaders();
    foreach ($urls as $url) {
      $file = $this->httpClient->get($url, $auth_headers);
      $json = $file->getBody();
      $filename = $this->generateFileName($url);
      $directory = 'private://civictheme_migrate';
      if ($this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY)) {
        $uri = "$directory/$filename";
        $path = $this->fileSystem->saveData($json, $uri);
        $filename = basename($path);
        $uri = "$directory/$filename";

        /** @var \Drupal\file\FileInterface $file */
        $file = $this->fileStorage->create(['uri' => $uri]);

        // Carry out validation.
        $errors = file_validate($file, $validators);
        if (!empty($errors)) {
          $error = array_pop($errors);
          throw new Exception($error);
        }
        $file->setPermanent();
        $file->save();
        $files[] = (int) $file->get('fid')->getString();
      }
    }

    return $files;
  }

  /**
   * Gets the authentication headers and other options for retrieving files.
   *
   * @return array
   *   Authentication header options.
   */
  protected function getAuthHeaders():array {
    $auth_type = $this->config->get('auth_type');
    if (empty($auth_type)) {
      return [];
    }
    switch ($auth_type) {
      case 'basic':
        return [
          'auth' => [
            $this->config->get('auth_username'),
            $this->config->get('auth_password'),
          ],
        ];

      // @todo implement other authentication methods as required.
      default:
        return [];
    }
  }

  /**
   * Generates a filename from a url JSON endpoint.
   *
   * @param string $url
   *   Endpoint url.
   *
   * @return string
   *   Generated file name.
   */
  protected function generateFileName($url):string {
    $url_parts = parse_url($url);
    $filename = !empty($url_parts['path']) ? preg_replace('/\//', '-', basename($url_parts['path'])) : 'configuration-file' . time();
    if (strpos($filename, '-') === 0) {
      $filename = substr($filename, 1);
    }
    if (strpos($filename, '.json') === FALSE && strpos($filename, '.txt') === FALSE) {
      $filename .= '.json';
    }

    return $filename;
  }

}
