<?php

namespace Drupal\civictheme;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Theme\ActiveTheme;
use Drupal\Core\Theme\ThemeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme config manager.
 *
 * Proxy to deal with any kind of theme configuration.
 */
class CivicthemeConfigManager implements ContainerInjectionInterface {

  /**
   * Current active theme.
   *
   * @var \Drupal\Core\Theme\ActiveTheme
   */
  protected $theme;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory.
   * @param \Drupal\Core\Theme\ThemeManager $theme_manager
   *   The theme manager.
   */
  public function __construct(ConfigFactory $config_factory, ThemeManager $theme_manager) {
    $this->configFactory = $config_factory;
    $this->themeManager = $theme_manager;
    $this->setTheme($this->themeManager->getActiveTheme());
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('theme.manager')
    );
  }

  /**
   * Load configuration with provided key.
   *
   * @param string $key
   *   The configuration key.
   * @param mixed $default
   *   Default value to return if the $key is not set. Defaults to NULL.
   *
   * @return mixed|null
   *   The value of the requested setting, NULL if the setting does not exist.
   */
  public function load($key, $default = NULL) {
    return theme_get_setting($key, $this->theme->getName()) ?? $default;
  }

  /**
   * Load configuration for a component with a provided key.
   *
   * @param string $name
   *   Component name.
   * @param string $key
   *   The configuration key.
   * @param mixed $default
   *   Default value to return if the $key is not set. Defaults to NULL.
   *
   * @return mixed|null
   *   The value of the requested setting, NULL if the setting does not exist.
   */
  public function loadForComponent($name, $key, $default = NULL) {
    return $this->load("components.{$name}.{$key}", $default);
  }

  /**
   * Save configuration with provided key and a value.
   *
   * @param string $key
   *   Identifier to store value in configuration.
   * @param mixed $value
   *   Value to associate with identifier.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function save($key, $value) {
    $theme_name = $this->theme->getName();
    $config = $this->configFactory->getEditable("$theme_name.settings");
    $config->set($key, $value)->save();

    return $this;
  }

  /**
   * Set active theme.
   *
   * @param \Drupal\Core\Theme\ActiveTheme $theme
   *   Active theme instance.
   *
   * @return $this
   *   Instance of the current class.
   */
  public function setTheme(ActiveTheme $theme) {
    $this->theme = $theme;

    return $this;
  }

}
