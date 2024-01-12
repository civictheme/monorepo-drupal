<?php

declare(strict_types=1);

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
final class CivicthemeConfigManager implements ContainerInjectionInterface {

  /**
   * Current active theme.
   *
   * @var \Drupal\Core\Theme\ActiveTheme
   */
  protected $theme;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory.
   * @param \Drupal\Core\Theme\ThemeManager $themeManager
   *   The theme manager.
   * @param \Drupal\Core\Extension\ThemeExtensionList $themeExtensionList
   *   The extension list.
   * @param \Drupal\civictheme\CivicthemeConfigImporter $configImporter
   *   The config importer.
   */
  public function __construct(protected ConfigFactory $configFactory, protected ThemeManager $themeManager, protected ThemeExtensionList $themeExtensionList, protected CivicthemeConfigImporter $configImporter) {
    $this->setTheme($this->themeManager->getActiveTheme());
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
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
  public function load($key, mixed $default = NULL) {
    // Return site slogan from system site settings.
    if ($key == 'components.site_slogan.content') {
      return $this->configFactory->getEditable('system.site')->get('slogan');
    }

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
  public function loadForComponent($name, $key, mixed $default = NULL) {
    return $this->load(sprintf('components.%s.%s', $name, $key), $default);
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
  public function save($key, mixed $value): static {
    // Set site slogan.
    if ($key == 'components.site_slogan.content') {
      $config = $this->configFactory->getEditable('system.site')->set('slogan', $value)->save();

      return $this;
    }

    $theme_name = $this->theme->getName();
    $config = $this->configFactory->getEditable($theme_name . '.settings');
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
  public function setTheme(ActiveTheme $theme): static {
    $this->theme = $theme;

    return $this;
  }

  /**
   * Reset settings to defaults.
   */
  public function resetToDefaults(): void {
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
      $theme_name . '.settings',
      $tokens
    );
  }

}
