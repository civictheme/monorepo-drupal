<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Component\Serialization\Json;
use Drupal\config\StorageReplaceDataWrapper;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageComparer;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Lock\LockBackendInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * CivicTheme config importer.
 *
 * Imports configurations from specified files.
 */
final class CivicthemeConfigImporter implements ContainerInjectionInterface {

  /**
   * Array of storages.
   *
   * @var array<\Drupal\Core\Config\StorageInterface>
   */
  protected $storages;

  /**
   * Constructor.
   *
   * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The event dispatcher used to notify subscribers of config import events.
   * @param \Drupal\Core\Config\ConfigManagerInterface $configManager
   *   The configuration manager.
   * @param \Drupal\Core\Lock\LockBackendInterface $lockPersistent
   *   The lock backend.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typedConfigManager
   *   The typed configuration manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\Extension\ModuleInstallerInterface $moduleInstaller
   *   The module installer.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $themeHandler
   *   The theme handler.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The string translation service.
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Config\StorageInterface $configStorage
   *   The config storage.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cacheConfig
   *   The cache backend.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The logger.
   *
   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(protected EventDispatcherInterface $eventDispatcher, protected ConfigManagerInterface $configManager, protected LockBackendInterface $lockPersistent, protected TypedConfigManagerInterface $typedConfigManager, protected ModuleHandlerInterface $moduleHandler, protected ModuleInstallerInterface $moduleInstaller, protected ThemeHandlerInterface $themeHandler, protected TranslationInterface $stringTranslation, protected ModuleExtensionList $moduleExtensionList, protected StorageInterface $configStorage, protected CacheBackendInterface $cacheConfig, protected MessengerInterface $messenger, protected LoggerChannelInterface $logger) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('event_dispatcher'),
      $container->get('config.manager'),
      $container->get('lock.persistent'),
      $container->get('config.typed'),
      $container->get('module_handler'),
      $container->get('module_installer'),
      $container->get('theme_handler'),
      $container->get('string_translation'),
      $container->get('extension.list.module'),
      $container->get('config.storage'),
      $container->get('cache.config'),
      $container->get('messenger'),
      $container->get('logger.factory')->get(CivicthemeConfigImporter::class)
    );
  }

  /**
   * Discover and import all configs within specified locations.
   *
   * @param string|array $locations
   *   A single or an array of locations to discover configs.
   * @param array $tokens
   *   Array of tokens to replace within configs while they are importing.
   */
  public function importConfigs($locations = [], array $tokens = []): void {
    $locations = is_array($locations) ? $locations : [$locations];

    foreach ($locations as $location) {
      $files = glob($location . '/*') ?: [];
      foreach ($files as $file) {
        $src = basename($file, '.yml');
        $dst = $this->replaceTokens($src, $tokens);
        $this->importSingleConfig($src, $locations, $dst, $tokens);
      }
    }
  }

  /**
   * Import a single config item.
   *
   * Can also be used to import into config with a different name.
   *
   * @param string $src_name
   *   Source config name to import.
   * @param string|array $locations
   *   Location or an array of locations to search for configuration files.
   * @param string $dst_name
   *   Destination config name to import. If not provided - defaults
   *   to $src_name.
   * @param array $tokens
   *   Optional array of tokens to replace while importing configuration.
   */
  public function importSingleConfig(string $src_name, $locations, $dst_name = NULL, array $tokens = []): void {
    $dst_name = $dst_name ?? $src_name;

    $locations = is_array($locations) ? $locations : [$locations];

    $config_data = $this->readConfig($src_name, $locations);
    $config_data = $this->replaceTokens($config_data, $tokens);

    unset($config_data['uuid']);

    $source_storage = new StorageReplaceDataWrapper($this->configStorage);
    $source_storage->replaceData($dst_name, $config_data);

    $storage_comparer = new StorageComparer($source_storage, $this->configStorage);
    $storage_comparer->createChangelist();

    $config_importer = new ConfigImporter(
      $storage_comparer,
      $this->eventDispatcher,
      $this->configManager,
      $this->lockPersistent,
      $this->typedConfigManager,
      $this->moduleHandler,
      $this->moduleInstaller,
      $this->themeHandler,
      $this->stringTranslation,
      $this->moduleExtensionList
    );

    try {
      $config_importer->import();
      $this->cacheConfig->delete($dst_name);
    }
    catch (\Exception $exception) {
      foreach ($config_importer->getErrors() as $error) {
        $this->logger->error($error);
        $this->messenger->addError($error);
      }

      throw $exception;
    }
  }

  /**
   * Read configuration from provided locations.
   *
   * @param string $id
   *   Configuration id.
   * @param array $locations
   *   Array of paths to lookup configuration files.
   *
   * @return mixed
   *   Configuration value.
   *
   * @throws \Exception
   *   If configuration file was not found in any specified location.
   *
   * @SuppressWarnings(PHPMD.MissingImport)
   */
  public function readConfig(string $id, array $locations) {
    foreach ($locations as $path) {
      if (file_exists($path . DIRECTORY_SEPARATOR . $id . '.yml')) {
        $this->storages[$path] = new FileStorage($path);

        return $this->storages[$path]->read($id);
      }
    }

    throw new \Exception('Configuration does not exist in any provided locations');
  }

  /**
   * Replace tokens within data keys and values.
   *
   * @param mixed $data
   *   Date to replace tokens in.
   * @param array $tokens
   *   Array of tokens to replace the data. Tokens with values of FALSE will be
   *   preserved.
   *
   * @return mixed
   *   Data with replaced tokens.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  protected function replaceTokens(mixed $data, array $tokens = []) {
    foreach ($tokens as $k => $v) {
      $key = str_replace('/', '\/', $k);
      $value = $v ? str_replace('/', '\/', (string) $v) : $v;
      unset($tokens[$k]);
      $tokens[$key] = $value;
    }

    $replace = array_filter($tokens);
    // Retrieve tokens that should be preserved.
    $preserve = array_diff_key($tokens, $replace);

    $preserve_in = [];
    $preserve_out = [];
    foreach (array_keys($preserve) as $k => $name) {
      $preserve_in[$name] = 'PRESERVE_BEGIN' . $k . 'PRESERVE_END';
      $preserve_out['PRESERVE_BEGIN' . $k . 'PRESERVE_END'] = $name;
    }

    $encoded = Json::encode($data);
    $encoded = strtr($encoded, $preserve_in);
    $encoded = strtr($encoded, $replace);
    $encoded = strtr($encoded, $preserve_out);

    return Json::decode($encoded);
  }

}
