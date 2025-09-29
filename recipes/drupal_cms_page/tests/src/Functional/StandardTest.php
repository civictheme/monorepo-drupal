<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_page\Functional;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_page')]
#[IgnoreDeprecations]
class StandardTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'standard';

  public function testCompatibilityWithStandard(): void {
    $dir = realpath(__DIR__ . '/../../..');
    $this->applyRecipe($dir);

    // The fields added by the recipe should exist.
    $field_definitions = $this->container->get(EntityFieldManagerInterface::class)
      ->getFieldDefinitions('node', 'page');
    $this->assertArrayHasKey('field_content', $field_definitions);
    $this->assertArrayHasKey('field_description', $field_definitions);
    $this->assertArrayHasKey('field_featured_image', $field_definitions);
    $this->assertArrayHasKey('field_tags', $field_definitions);

    // None of our fields should be visible on the edit form.
    $account = $this->drupalCreateUser();
    $account->addRole('content_editor')->save();
    $this->drupalLogin($account);
    $this->drupalGet('/node/add/page');
    $assert_session = $this->assertSession();
    $assert_session->statusCodeEquals(200);
    $assert_session->fieldNotExists('Content');
    $assert_session->fieldNotExists('field_description[0][value]');
    $assert_session->fieldNotExists('Featured image');
    $assert_session->fieldNotExists('Tags');
    // The Body field from Standard should be visible.
    $assert_session->fieldExists('Body');
  }

}
