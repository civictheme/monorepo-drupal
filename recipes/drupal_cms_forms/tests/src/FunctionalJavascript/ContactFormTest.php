<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_forms\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_forms')]
#[IgnoreDeprecations]
final class ContactFormTest extends WebDriverTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

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

    $this->drupalPlaceBlock('system_menu_block:main', [
      'label' => 'Main menu',
    ]);
  }

  public function testContactFormFields(): void {
    $this->drupalGet('<front>');

    $assert_session = $this->assertSession();
    $assert_session->elementExists('css', 'h2:contains("Main menu") + ul')
      ->clickLink('Contact');
    $assert_session->elementAttributeExists('named', ['field', 'Name'], 'required');
    $assert_session->elementAttributeExists('named', ['field', 'Email'], 'required');
    $assert_session->elementAttributeExists('named', ['field', 'Message'], 'required');

    $captcha = $assert_session->elementExists('css', 'fieldset > legend:contains("CAPTCHA")')
      ->getParent();
    $this->assertTrue($captcha->isVisible());
    $assert_session->buttonExists('Click to start verification', $captcha);
    $this->assertFalse($assert_session->fieldExists('url')->isVisible());
  }

}
