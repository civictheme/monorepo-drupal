<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_privacy_basic\Functional;

use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_privacy_basic')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block'];

  public function test(): void {
    $dir = realpath(__DIR__ . '/../../..');

    // The recipe should apply cleanly.
    $this->applyRecipe($dir);
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    // The footer menu should not be visible by default.
    $this->drupalPlaceBlock('system_menu_block:footer', ['label' => 'Footer']);
    $this->drupalGet('<front>');
    $footer_menu = 'nav > h2:contains("Footer") + ul';
    $assert_session = $this->assertSession();
    $assert_session->elementNotExists('css', $footer_menu);

    // Publish the privacy policy and ensure it shows up in the footer.
    $privacy_policy = $this->container->get(EntityRepositoryInterface::class)
      ->loadEntityByUuid('node', '00d105b3-6f05-40c6-a289-3dd61c89480e');
    $this->assertIsObject($privacy_policy);
    $privacy_policy->moderation_state = 'published';
    $privacy_policy->save();
    $this->getSession()->reload();
    $footer_menu = $assert_session->elementExists('css', $footer_menu);
    // The privacy settings aren't linked in the menu until a relevant Klaro app
    // is enabled.
    $this->assertFalse($footer_menu->hasLink('My privacy settings'));
    $this->assertTrue($footer_menu->hasLink('Privacy policy'));
  }

}
