<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_admin_theme\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @group drupal_cms_admin_ui
 */
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
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    $account = $this->drupalCreateUser(['access navigation']);
    $this->drupalLogin($account);
    $assert_session = $this->assertSession();
    // The Help module is not installed, so a link to it should not be present
    // in the navigation.
    $footer = $assert_session->elementExists('css', 'nav > h3:contains("Administrative toolbar footer")')
      ->getParent();
    $assert_session->elementNotExists('named', ['link', 'Help'], $footer);

    $this->drupalLogout();
    // Ensure that there are no broken blocks in the navigation (or anywhere
    // else). We need to test this with the root user because they have all
    // permissions, and therefore any broken blocks in the navigation will be
    // obvious to them.
    $this->drupalLogin($this->rootUser);
    $assert_session->pageTextNotContains('This block is broken or missing.');
  }

}
