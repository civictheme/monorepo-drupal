<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_content_type_base\Functional;

use Composer\InstalledVersions;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_content_type_base')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;
  use TaxonomyTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'olivero';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $dir = realpath(__DIR__ . '/../../..');
    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    $dir = InstalledVersions::getInstallPath('drupal/drupal_cms_page');
    $this->applyRecipe($dir);
  }

  public function testTaxonomyTermView(): void {
    // The `tags` vocabulary should exist.
    $vocabulary = Vocabulary::load('tags');
    $this->assertInstanceOf(Vocabulary::class, $vocabulary);
    $tag = $this->createTerm($vocabulary)->id();

    // Create a published page with a tag so that we can test the
    // `taxonomy_term` view.
    $this->drupalCreateNode([
      'type' => 'page',
      'title' => "Card Me",
      'moderation_state' => 'published',
      'field_tags' => [$tag],
    ]);
    $this->drupalGet('/taxonomy/term/' . $tag);
    $assert_session = $this->assertSession();
    // We should be able to see the view as an anonymous user, and it should be
    // using the `card` view mode.
    $assert_session->statusCodeEquals(200);
    $card = $assert_session->elementExists('css', '.node--view-mode-card');
    $assert_session->elementExists('named', ['link', 'Card Me'], $card);
  }

  public function testContentEditorPermissions(): void {
    // Create an unpublished page.
    $node = $this->drupalCreateNode(['type' => 'page']);
    $this->assertFalse($node->isPublished());

    // Log in as a content editor and ensure we can see the front page,
    // regardless of its publication status.
    $account = $this->drupalCreateUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);
    $this->drupalGet($node->toUrl());
    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);
    $assert_session->pageTextContains($node->getTitle());
    $node->set('moderation_state', 'published')->save();
    $this->assertTrue($node->isPublished());
    $this->getSession()->reload();
    $assert_session->statusCodeEquals(200);

    // Create another unpublished page and ensure we can see it in the
    // list of moderated content.
    $unpublished = $this->drupalCreateNode(['type' => 'page']);
    $this->assertFalse($unpublished->isPublished());
    $this->drupalGet("/admin/content/moderated");
    $assert_session->linkExists($unpublished->getTitle());

    // The trash should be accessible to content editors.
    $this->drupalGet('/admin/content/trash');
    $assert_session->statusCodeEquals(200);
    $this->drupalGet('/admin/content/trash/node');
    $assert_session->statusCodeEquals(200);
  }

}
