<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_seo_basic\Functional;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

/**
 * Tests basic SEO optimizations aimed at machine readability and performance.
 */
#[Group('drupal_cms_seo_basic')]
#[IgnoreDeprecations]
class BasicSeoTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'content_translation',
    'filter_test',
    'node',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Disable CSS and JavaScript aggregation, to prove that the recipe
    // re-enables it.
    $this->config('system.performance')
      ->set('css.preprocess', FALSE)
      ->set('js.preprocess', FALSE)
      ->save();

    $dir = realpath(__DIR__ . '/../../..');
    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    // Ensure that the recipe has enabled CSS and JS aggregation.
    $config = $this->config('system.performance');
    $this->assertTrue($config->get('css.preprocess'));
    $this->assertTrue($config->get('js.preprocess'));

    $this->drupalCreateContentType(['type' => 'page']);
  }

  public function testJsonLdBreadcrumbListExists(): void {
    $this->drupalPlaceBlock('system_breadcrumb_block');

    $node = $this->drupalCreateNode(['type' => 'page']);
    $this->drupalGet($node->toUrl());
    // Ensure that the machine-readable breadcrumb list exists, but don't bother
    // checking its internals. We trust Easy Breadcrumb to get it right.
    $this->assertSession()
      ->elementAttributeContains('css', 'script:contains("BreadcrumbList")', 'type', 'application/ld+json');
  }

  public function test404NotLogged(): void {
    $this->container->get(ModuleInstallerInterface::class)->install(['dblog']);

    $this->drupalGet('/nope');
    $account = $this->drupalCreateUser(['access site reports']);
    $this->drupalLogin($account);
    $this->drupalGet('/admin/reports/page-not-found');
    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);
    $assert_session->pageTextNotContains('nope');
  }

  public function testContentEditorsCanManageRedirects(): void {
    $account = $this->createUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);
    $this->drupalGet('/admin/config/search/redirect');
    $this->assertSession()->statusCodeEquals(200);
  }

}
