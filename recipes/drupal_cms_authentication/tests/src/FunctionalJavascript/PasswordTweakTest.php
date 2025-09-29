<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_authentication\FunctionalJavascript;

use Behat\Mink\Element\NodeElement;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[Group('drupal_cms_authentication')]
#[IgnoreDeprecations]
final class PasswordTweakTest extends WebDriverTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function testPasswordFieldIsHiddenIfRegistrantWillBeNotified(): void {
    $this->applyRecipe(__DIR__ . '/../../..');

    $this->drupalLogin($this->rootUser);
    $this->drupalGet('/admin/people/create');
    $assert_session = $this->assertSession();
    $assert_session->checkboxChecked('Notify user of new account');
    $password_field = $assert_session->fieldExists('Password');
    $confirm_password_field = $assert_session->fieldExists('Confirm password');
    $this->assertFalse($password_field->isVisible());
    $this->assertFalse($confirm_password_field->isVisible());
    $this->assertEmpty($password_field->getValue());
    $this->assertEmpty($confirm_password_field->getValue());
    $page = $this->getSession()->getPage();
    $page->uncheckField('Notify user of new account');
    $to_be_visible = fn (NodeElement $field): bool => $field->isVisible();
    $this->assertTrue($password_field->waitFor(3, $to_be_visible));
    $this->assertTrue($confirm_password_field->waitFor(3, $to_be_visible));
    $page->checkField('Notify user of new account');
    $page->fillField('Email', 'chef@drupal.local');
    $page->fillField('Username', 'chef');
    $page->pressButton('Create new account');
    $assert_session->statusMessageContains('A welcome message with further instructions has been emailed to the new user chef.');
  }

}
