<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_starter\Functional;

use Composer\InstalledVersions;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @group drupal_cms_starter
 */
class ContentEditingTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * @testWith ["drupal/drupal_cms_blog", "blog", false]
   *   ["drupal/drupal_cms_case_study", "case_study", false]
   *   ["drupal/drupal_cms_events", "event", false]
   *   ["drupal/drupal_cms_news", "news", false]
   *   ["drupal/drupal_cms_page", "page", true]
   *   ["drupal/drupal_cms_person", "person", false]
   *   ["drupal/drupal_cms_project", "project", false]
   */
  public function testMenuSettingsVisibility(string $recipe_name, string $content_type, bool $has_menu_settings): void {
    // Apply the recipe for the given content type.
    $dir = InstalledVersions::getInstallPath($recipe_name);
    $this->applyRecipe($dir);

    $account = $this->drupalCreateUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);
    $this->drupalGet("/node/add/$content_type");

    // Check menu settings visibility.
    $assert_session = $this->assertSession();
    if ($has_menu_settings) {
      $assert_session->pageTextContains('Menu settings');
    }
    else {
      $assert_session->pageTextNotContains('Menu settings');
    }
    // Verify the form loaded without errors.
    $assert_session->statusCodeEquals(200);
  }

}
