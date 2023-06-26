<?php

namespace Drupal\civictheme_migrate\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MigrationPluginSubscriber.
 *
 * This class is used to modify the migration plugin.
 *
 * @package Drupal\civictheme_migrate\EventSubscriber
 */
class MigrationPluginSubscriber implements EventSubscriberInterface {

  /**
   * Constructor.
   */
  public function __construct(protected ConfigFactoryInterface $configFactory) {
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // The MigrateEvents::PRE_IMPORT event occurs before a migration import.
    $events[MigrateEvents::PRE_IMPORT] = ['onMigratePreImport'];

    return $events;
  }

  /**
   * Pre-import event callback.
   */
  public function onMigratePreImport(MigrateImportEvent $event): void {
    $migration = $event->getMigration();

    $process = $migration->getProcess();
    foreach ($process as $destination_field => $process_plugins) {
      if (isset($process_plugins['plugin'])) {
        $process_plugins = [$process_plugins];
      }
      foreach ($process_plugins as $k => $process_plugin) {
        if (isset($process_plugin['plugin']) && $process_plugin['plugin'] === 'file_import') {
          $auth = $this->getAuth();
          if (!empty($auth)) {
            // Treat authentication types differently.
            if ($auth['type'] == 'basic') {
              $process[$destination_field][$k]['guzzle_options']['auth'] = [
                $auth['username'],
                $auth['password'],
              ];
            }
          }
        }

        if (isset($process_plugin['plugin']) && $process_plugin['plugin'] === 'content_components') {
          $domains = $this->configFactory->get('civictheme_migrate.settings')->get('local_domains');
          $process[$destination_field][$k]['local_domains'] = array_merge($process[$destination_field][$k]['local_domains'] ?? [], $domains);
        }
      }
    }

    $migration->setProcess($process);
  }

  /**
   * Get authentication details.
   *
   * @return mixed
   *   The authentication details.
   */
  protected function getAuth(): mixed {
    $auth = [];

    $config = $this->configFactory->get('civictheme_migrate.settings')->get('remote_authentication');
    if ($config['type'] == 'basic') {
      if (!empty($config['basic']['username']) && !empty($config['basic']['password'])) {
        $auth = [
          'type' => 'basic',
          'username' => $config['basic']['username'],
          'password' => $config['basic']['password'],
        ];
      }
    }

    return $auth;
  }

}
