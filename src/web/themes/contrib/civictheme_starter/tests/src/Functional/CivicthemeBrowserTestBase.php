<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme_starter\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Class CivicthemeBrowserTestBase.
 *
 * Base test class for all tests that require CivicTheme to be installed.
 */
abstract class CivicthemeBrowserTestBase extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Custom theme to test.
   *
   * @var string
   */
  protected $customTheme = 'civictheme_starter';

  /**
   * Whether to apply the content model recipes before enabling the theme.
   *
   * FALSE proves the theme installs standalone, without the content model.
   *
   * @var bool
   */
  protected $applyRecipes = TRUE;

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings("PHPMD.StaticAccess")
   */
  protected function setUp(): void {
    parent::setUp();

    $container = $this->container;

    $theme_path = dirname((string) $container->get('extension.list.theme')->reset()->getList()[$this->customTheme]->getPathname());

    // Install the modules the theme's own (optional) config depends on; the
    // recipes install everything the content model needs.
    require_once $theme_path . '/theme-settings.provision.inc';
    $modules = _civictheme_get_theme_dependencies($this->customTheme, TRUE);
    if ($modules !== []) {
      $container->get('module_installer')->install($modules);
    }

    // Refresh container after installing modules.
    $this->container = \Drupal::getContainer();
    $container = $this->container;

    // Ensure the default theme is installed. The theme must be installed
    // before the recipes apply: the entity display config they ship uses
    // layout plugins the theme provides.
    $container->get('theme_installer')->install([$this->customTheme], TRUE);

    $system_theme_config = $container->get('config.factory')->getEditable('system.theme');
    if ($system_theme_config->get('default') !== $this->customTheme) {
      $system_theme_config
        ->set('default', $this->customTheme)
        ->save();
    }

    if ($this->applyRecipes) {
      $this->applyRecipe($theme_path . '/recipes/civictheme_site');
      $this->applyRecipe($theme_path . '/recipes/civictheme_search');

      // The theme's optional block placements depend on config the recipes
      // created; re-run the optional config sweep so they are picked up.
      $this->container->get('config.installer')->installOptionalConfig();
    }

    // Reset all caches to ensure that the newly enabled modules, themes and
    // configurations are picked up.
    $this->resetAll();
  }

}
