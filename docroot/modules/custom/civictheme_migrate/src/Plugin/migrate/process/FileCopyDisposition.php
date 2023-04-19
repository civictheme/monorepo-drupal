<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\process;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\MigrateProcessInterface;
use Drupal\migrate\Row;
use Drupal\migrate_file\Plugin\migrate\process\FileImport;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Imports a file from a remote URL, adding authentication, if required.
 *
 * Also, infers file names based on the content disposition header.
 *
 * @MigrateProcessPlugin(
 *    id = "file_copy_disposition"
 * )
 */
class FileCopyDisposition extends FileImport {

  /**
   * The client used to send HTTP requests.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs a file_copy process plugin.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param array $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrappers
   *   The stream wrapper manager service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   * @param \Drupal\migrate\Plugin\MigrateProcessInterface $download_plugin
   *   An instance of the download plugin for handling remote URIs.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The configuration factory.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, StreamWrapperManagerInterface $stream_wrappers, FileSystemInterface $file_system, MigrateProcessInterface $download_plugin, ConfigFactory $config_factory, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $stream_wrappers, $file_system, $download_plugin);
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('stream_wrapper_manager'),
      $container->get('file_system'),
      $container->get('plugin.manager.migrate.process')->createInstance('download', $configuration),
      $container->get('config.factory'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($url, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$url) {
      return NULL;
    }

    $options = [];
    $options['header'] = ['Accept-Encoding: gzip, deflate'];
    $options['http_errors'] = FALSE;

    $authentication_settings = $this->configFactory->get('civictheme_migrate.settings')->get('remote_authentication');
    if ($authentication_settings['type'] == 'basic') {
      if (!empty($authentication_settings['basic']['username']) && !empty($authentication_settings['basic']['password'])) {
        $options['auth'] = [
          $authentication_settings['basic']['username'],
          $authentication_settings['basic']['password'],
        ];
      }
    }

    $response = $this->httpClient->request('GET', $url, $options);
    if ($response->getStatusCode() != '200') {
      throw new MigrateSkipRowException("Unable to download file $url: {$response->getBody()}");
    }

    $data = $response->getBody();

    $uuid = $row->getSourceProperty('uuid');

    $filename = urldecode(basename($url));
    $filename = "{$uuid}_$filename";
    $filename = "temporary://{$filename}";

    file_put_contents($filename, (string) $data);
    $file_path = $this->fileSystem->realpath($filename);

    return parent::transform($file_path, $migrate_executable, $row, $destination_property);
  }

}
