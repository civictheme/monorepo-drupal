<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_ai\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @group drupal_cms_ai
 */
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function test(): void {
    // The recipe block config requires an admin theme to be set.
    $this->config('system.theme')->set('admin', $this->defaultTheme)->save();
    $dir = realpath(__DIR__ . '/../../..');
    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    // The privacy settings should be available to anonymous users.
    $this->drupalPlaceBlock('system_menu_block:footer', ['label' => 'Footer']);
    $this->drupalGet('<front>');
    $footer_menu = $this->assertSession()
      ->elementExists('css', 'nav > h2:contains("Footer") + ul');
    $this->assertTrue($footer_menu->hasLink('My privacy settings'));
  }

}
