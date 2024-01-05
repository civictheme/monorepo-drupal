<?php

namespace Drupal\civictheme_dev\EventSubscriber;

use Drupal\config_devel\Event\ConfigDevelSaveEvent;
use Drupal\config_devel\EventSubscriber\ConfigDevelSubscriberBase;
use Drupal\config_filter\Plugin\ConfigFilterPluginManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * CivicthemeDevConfigDevelSubscriber subscriber for ConfigDevel events.
 *
 * Integrates custom config_ignore filter into config_devel so that exporting
 * with config_devel would respect config_ignore filtering.
 *
 * Currently, limited to a single custom config_ignore filter defined in this
 * module.
 */
class CivicthemeDevConfigDevelSubscriber extends ConfigDevelSubscriberBase implements EventSubscriberInterface {

  /**
   * Constructs the ConfigDevelAutoExportSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Config\ConfigManagerInterface $config_manager
   *   The configuration manager.
   * @param \Drupal\config_filter\Plugin\ConfigFilterPluginManager $configFilterPluginManager
   *   The config ignore plugin manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ConfigManagerInterface $config_manager, protected ConfigFilterPluginManager $configFilterPluginManager) {
    parent::__construct($config_factory, $config_manager);
    $this->configFactory = $config_factory;
    $this->configManager = $config_manager;
  }

  /**
   * React to configuration ConfigEvent::SAVE events.
   *
   * @param \Drupal\config_devel\Event\ConfigDevelSaveEvent $event
   *   The event to process.
   */
  public function onConfigDevelSave(ConfigDevelSaveEvent $event): void {
    $filenames = $event->getFileNames();

    if (empty($filenames)) {
      return;
    }

    $filename = reset($filenames);
    $name = basename((string) $filename, '.yml');
    $data = $event->getData();

    /** @var \Drupal\civictheme_dev\Plugin\ConfigFilter\CivicthemeDevIgnoreFilter $plugin */
    $plugin = $this->configFilterPluginManager->createInstance('civictheme_dev_config_ignore');

    $data = $plugin->filterWrite($name, $data);

    $event->setData($data);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [['onConfigDevelSave', 10]];
  }

}
