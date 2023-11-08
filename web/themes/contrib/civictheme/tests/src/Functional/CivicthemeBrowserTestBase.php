<?php

namespace Drupal\Tests\civictheme\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Class CivicthemeBrowserTestBase.
 *
 * Base test class for all tests that require CivicTheme to be installed.
 */
abstract class CivicthemeBrowserTestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Custom theme to test.
   *
   * @var string
   */
  protected $customTheme = 'civictheme';

  /**
   * Whether to install optional dependencies.
   *
   * @var bool
   */
  protected $optionalDependencies = TRUE;

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function setUp(): void {
    parent::setUp();

    $container = $this->container;

    require_once dirname($container->get('theme_handler')->rebuildThemeData()[$this->customTheme]->getPathname()) . '/theme-settings.provision.inc';
    $modules = _civictheme_get_theme_dependencies($this->customTheme, $this->optionalDependencies);
    $container->get('module_installer')->install($modules);

    // Refresh container after installing modules.
    $this->container = \Drupal::getContainer();
    $container = $this->container;

    // Ensure the default theme is installed.
    $container->get('theme_installer')->install([$this->customTheme], TRUE);

    $system_theme_config = $container->get('config.factory')->getEditable('system.theme');
    if ($system_theme_config->get('default') !== $this->customTheme) {
      $system_theme_config
        ->set('default', $this->customTheme)
        ->save();
    }
  }

}
