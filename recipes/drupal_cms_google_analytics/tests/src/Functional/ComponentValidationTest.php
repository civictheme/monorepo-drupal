<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_google_analytics\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_google_analytics')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function test(): void {
    $dir = realpath(__DIR__ . '/../../..');

    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it twice to prove it is idempotent.
    $this->applyRecipe($dir);

    // The privacy settings should be available to anonymous users.
    $this->drupalPlaceBlock('system_menu_block:footer', ['label' => 'Footer']);
    $this->drupalGet('<front>');
    $footer_menu = $this->assertSession()
      ->elementExists('css', 'nav > h2:contains("Footer") + ul');
    $this->assertTrue($footer_menu->hasLink('My privacy settings'));
  }

}
