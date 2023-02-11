<?php

namespace Drupal\civictheme;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeExtensionList;
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
   * The theme extension list.
   *
   * @var \Drupal\Core\Extension\ThemeExtensionList
   */
  protected $themeExtensionList;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory.
   * @param \Drupal\Core\Theme\ThemeManager $theme_manager
   *   The theme manager.
   * @param \Drupal\Core\Extension\ThemeExtensionList $theme_extension_list
   *   The extension list.
   * @param \Drupal\civictheme\CivicthemeConfigImporter $config_importer
   *   The config importer.
   */
  public function __construct(ConfigFactory $config_factory, ThemeManager $theme_manager, ThemeExtensionList $theme_extension_list, CivicthemeConfigImporter $config_importer) {
    $this->configFactory = $config_factory;
    $this->themeManager = $theme_manager;
    $this->themeExtensionList = $theme_extension_list;
    $this->configImporter = $config_importer;
    $this->setTheme($this->themeManager->getActiveTheme());
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('theme.manager'),
      $container->get('extension.list.theme'),
      $container->get('class_resolver')->getInstanceFromDefinition(CivicthemeConfigImporter::class)
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

  /**
   * Reset settings to defaults.
   */
  public function resetToDefaults() {
    $base_theme_name = 'civictheme';
    $base_theme_path = $this->themeExtensionList->getPath($base_theme_name);

    $theme_name = $this->theme->getName();
    $theme_path = $this->themeExtensionList->getPath($theme_name);

    $tokens = [
      'themes/contrib/civictheme' => $theme_path,
      $base_theme_name => $theme_name,
    ];

    if (!array_key_exists($base_theme_path, $tokens)) {
      $tokens[$base_theme_path] = $theme_path;
    }

    $this->configImporter->importSingleConfig(
      'civictheme.settings',
      $base_theme_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'install',
      "$theme_name.settings",
      $tokens
    );
  }

}
