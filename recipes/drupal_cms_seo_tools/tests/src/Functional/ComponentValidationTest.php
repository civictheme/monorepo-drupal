<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_seo_tools\Functional;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigInstallerInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_seo_tools')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait {
    applyRecipe as traitApplyRecipe;
  }

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create a content type so we can test the changes made by the recipe.
    $this->drupalCreateContentType(['type' => 'test'])->id();
  }

  private function applyRecipe(mixed ...$arguments): void {
    $dir = realpath(__DIR__ . '/../../..');
    $this->traitApplyRecipe($dir, ...$arguments);
  }

  public function test(): void {
    // The recipe should apply cleanly.
    $this->applyRecipe();
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe();

    // There should be an SEO image field on our test content type, referencing
    // image media.
    $field_settings = FieldConfig::loadByName('node', 'test', 'field_seo_image')?->getSettings();
    $this->assertIsArray($field_settings);
    $this->assertSame('default:media', $field_settings['handler']);
    $this->assertContains('image', $field_settings['handler_settings']['target_bundles']);

    // Check sitemap works as expected for anonymous users.
    $this->checkSitemap();

    // Check sitemap works as expected for authenticated users too.
    $authenticated = $this->createUser();
    $this->drupalLogin($authenticated);
    $this->checkSitemap();
  }

  public function testAutomaticSitemapSettings(): void {
    $this->applyRecipe();

    // We should have Simple Sitemap settings for the extant content type.
    $settings = $this->container->get('config.storage')
      ->listAll('simple_sitemap.bundle_settings');
    $this->assertSame(['simple_sitemap.bundle_settings.default.node.test'], $settings);

    $get_settings = function (string $node_type): Config {
      return $this->config("simple_sitemap.bundle_settings.default.node.$node_type");
    };
    // If we create a new content type programmatically, Simple Sitemap settings
    // should be generated for it automatically.
    $node_type = $this->drupalCreateContentType()->id();
    $this->assertFalse($get_settings($node_type)->isNew());

    // If we create a new content type in the UI, Simple Sitemap settings should
    // NOT be automatically generated.
    $account = $this->createUser([
      'administer content types',
      'administer sitemap settings',
    ]);
    $this->drupalLogin($account);
    $this->drupalGet('/admin/structure/types/add');
    $node_type = $this->randomMachineName();
    $this->submitForm([
      'name' => $node_type,
      'type' => $node_type,
      'simple_sitemap[default][index]' => 0,
    ], 'Save');
    $this->assertTrue($get_settings($node_type)->isNew());

    // Extant settings should not be changed...
    $get_settings('test')->set('priority', '0.3')->save();
    $this->assertSame('0.3', $get_settings('test')->get('priority'));
    // ...even if we reapply the recipe...
    $this->applyRecipe();
    $this->assertSame('0.3', $get_settings('test')->get('priority'));
    // ...or sync config (here, we are simulating that the priority was changed
    // by a config sync).
    $this->container->get(ConfigInstallerInterface::class)->setSyncing(TRUE);
    $get_settings('test')->set('priority', '0.2')->save();
    $this->assertSame('0.2', $get_settings('test')->get('priority'));
  }

  /**
   * Checks that the sitemap is accessible and contains the expected links.
   */
  private function checkSitemap(): void {
    // Create a main menu link to ensure it shows up in the site map.
    $node = $this->drupalCreateNode(['type' => 'test']);
    $menu_link = MenuLinkContent::create([
      'title' => $node->getTitle(),
      'link' => 'internal:' . $node->toUrl()->toString(),
      'menu_name' => 'main',
    ]);
    $menu_link->save();

    $this->drupalGet('/sitemap');

    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);
    $assert_session->linkByHrefNotExists('/rss.xml');

    $site_map = $assert_session->elementExists('css', '.sitemap');
    $site_name = $this->config('system.site')->get('name');
    $this->assertTrue($site_map->hasLink("Front page of $site_name"), 'Front page link does not appear in the site map.');
    $this->assertTrue($site_map->hasLink($menu_link->label()), 'Main menu links do not appear in the site map.');
  }

}
