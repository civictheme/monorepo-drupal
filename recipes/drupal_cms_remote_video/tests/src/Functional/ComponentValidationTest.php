<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_remote_video\Functional;

use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\media\Entity\Media;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_remote_video')]
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
    // Apply it again to prove that it is idempotent.
    $this->applyRecipe($dir);

    // The footer menu should not be visible to anonymous users yet, because
    // the privacy settings have not been enabled.
    $this->drupalPlaceBlock('system_menu_block:footer', ['label' => 'Footer']);
    $this->drupalGet('<front>');
    $footer_menu_selector = 'nav > h2:contains("Footer") + ul';
    $assert_session = $this->assertSession();
    $assert_session->elementNotExists('css', $footer_menu_selector);

    // Create a remote video media entity and make sure that the footer menu
    // will then appear, containing the privacy settings link but not the
    // privacy policy.
    Media::create([
      'bundle' => 'remote_video',
      'status' => 1,
      'name' => 'Driesnote',
      'field_media_oembed_video' => 'https://www.youtube.com/watch?v=U6o8ou71oyE',
    ])->save();
    $this->getSession()->reload();
    $footer_menu = $assert_session->elementExists('css', $footer_menu_selector);
    $this->assertTrue($footer_menu->hasLink('My privacy settings'));
    $this->assertFalse($footer_menu->hasLink('Privacy policy'));
  }

}
