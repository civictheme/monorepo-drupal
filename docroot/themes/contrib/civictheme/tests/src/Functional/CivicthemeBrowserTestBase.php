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
  protected static $modules = [
    'block',
    'block_content',
    'ckeditor',
    'components',
    'config',
    'content_moderation',
    'datetime_range',
    'field',
    'field_group',
    'file',
    'help',
    'image',
    'inline_form_errors',
    'layout_builder',
    'layout_builder_restrictions',
    'layout_discovery',
    'layout_test',
    'linkit',
    'media',
    'media_library',
    'menu_block',
    'migrate',
    'node',
    'options',
    'paragraphs',
    'path_alias',
    'pathauto',
    'redirect',
    'rest',
    'shortcut',
    'system',
    'taxonomy',
    'user',
    'webform',
  ];

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
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

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
