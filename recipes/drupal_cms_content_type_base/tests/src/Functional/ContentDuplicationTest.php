<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_content_type_base\Functional;

use Composer\InstalledVersions;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\TestWith;

#[Group('drupal_cms_content_type_base')]
#[IgnoreDeprecations]
class ContentDuplicationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalPlaceBlock('page_title_block');
    $this->drupalPlaceBlock('local_tasks_block');
  }

  #[TestWith(['drupal/drupal_cms_blog', 'blog'])]
  #[TestWith(['drupal/drupal_cms_case_study', 'case_study'])]
  #[TestWith(['drupal/drupal_cms_events', 'event'])]
  #[TestWith(['drupal/drupal_cms_news', 'news'])]
  #[TestWith(['drupal/drupal_cms_page', 'page'])]
  #[TestWith(['drupal/drupal_cms_person', 'person'])]
  #[TestWith(['drupal/drupal_cms_project', 'project'])]
  public function testContentDuplication(string $recipe_name, string $content_type): void {
    $dir = InstalledVersions::getInstallPath($recipe_name);
    $this->applyRecipe($dir);

    $account = $this->drupalCreateUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);

    $original_title = 'Fun Times';
    $interstitial_message = sprintf('You are duplicating "%s"', $original_title);

    $original = $this->drupalCreateNode([
      'type' => $content_type,
      'title' => $original_title,
      'field_description' => $this->getRandomGenerator()->sentences(4),
    ]);
    $this->drupalGet($original->toUrl());
    // Duplicate tje node from its canonical route.
    $page = $this->getSession()->getPage();
    $page->clickLink('Duplicate');
    $assert_session = $this->assertSession();
    $assert_session->statusMessageContains($interstitial_message);
    $assert_session->fieldValueEquals('title[0][value]', $original_title);
    $page->fillField('title[0][value]', "$original_title, cloned by tab");
    $page->pressButton('Save');
    $assert_session->elementTextEquals('css', 'h1', "$original_title, cloned by tab");

    // Duplicate it again from the operation dropdown in the administrative
    // content list.
    $this->drupalGet('/admin/content');
    $assert_session->elementExists('named', ['link', 'Fun Times'])
      // Traverse upwards to the containing table row.
      ->getParent()
      ->getParent()
      ->clickLink('Duplicate');
    $assert_session->statusMessageContains($interstitial_message);
    $assert_session->fieldValueEquals('title[0][value]', $original_title);
    $page->fillField('title[0][value]', "$original_title, cloned by admin operation");
    $page->pressButton('Save');
    // We should be back on the administrative content list, and the duplicated
    // node should exist.
    $assert_session->addressEquals('/admin/content');
    $assert_session->linkExists("$original_title, cloned by admin operation");
  }

}
