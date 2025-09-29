<?php

declare(strict_types=1);

namespace Drupal\Tests\drupal_cms_authentication\Functional;

use Drupal\Core\Test\AssertMailTrait;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use Drupal\user\UserInterface;

#[Group('drupal_cms_authentication')]
#[IgnoreDeprecations]
class ComponentValidationTest extends BrowserTestBase {

  use AssertMailTrait;
  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function test(): void {
    // Apply the recipe twice to prove it applies cleanly and is idempotent.
    $dir = realpath(__DIR__ . '/../../..');
    $this->applyRecipe($dir);
    $this->applyRecipe($dir);

    $assert_session = $this->assertSession();

    // A 403 should redirect to the login page, forwarding to the original
    // destination.
    $this->drupalGet('/admin');
    $assert_session->statusCodeEquals(403);
    $assert_session->hiddenFieldValueEquals('form_id', 'user_login_form');
    $assert_session->buttonExists('Log in');

    // We should be able to log in with our email address. Upon logging out, we
    // should be redirected back to the login page.
    $this->drupalGet('/user/login');
    $this->submitForm([
      'name' => $this->rootUser->getEmail(),
      'pass' => $this->rootUser->passRaw,
    ], 'Log in');
    $assert_session->addressEquals('/user/' . $this->rootUser->id());
    $this->drupalLogout();
    $assert_session->addressEquals('/user/login');

    // We shouldn't get any special redirection if we're resetting our password.
    $this->drupalGet('/user/password');
    $this->submitForm([
      'name' => $this->rootUser->getAccountName(),
    ], 'Submit');
    $mail = $this->getMails();
    $this->assertNotEmpty($mail);
    $this->assertSame('user_password_reset', $mail[0]['id']);
    $this->resetPasswordFromMail($mail[0]);

    // If anonymous users are allowed to register accounts, ensure we can
    // register an account when logged out, then log in with it after resetting
    // the password.
    $this->drupalLogout();
    $this->config('user.settings')
      ->set('register', UserInterface::REGISTER_VISITORS)
      ->save();
    $this->drupalGet('/user/register');
    $assert_session->statusCodeEquals(200);
    $this->submitForm([
      'mail' => 'test@example.com',
      'name' => 'Test',
    ], 'Create new account');
    // The form should have submitted successfully, and a password reset link
    // should have been sent.
    $assert_session->statusCodeEquals(200);
    $mail = $this->getMails();
    $this->assertNotEmpty($mail);
    $this->assertSame('user_register_no_approval_required', $mail[1]['id']);
    $this->resetPasswordFromMail($mail[1]);
    // We should now be able to log in with the reset password.
    $this->drupalLogout();
    $this->drupalGet('/user/login');
    $this->submitForm([
      'name' => 'test@example.com',
      'pass' => 'my password',
    ], 'Log in');
    $assert_session->addressMatches('/\/user\/\d+$/');
  }

  /**
   * Resets a user password using an emailed reset link.
   *
   * @param array $mail
   *   The password reset email, from ::getMails().
   */
  private function resetPasswordFromMail(array $mail): void {
    $matches = [];
    preg_match('/^https?:.+/m', $mail['body'], $matches);
    $this->assertNotEmpty($matches);
    $this->drupalGet($matches[0]);

    $assert_session = $this->assertSession();
    $assert_session->addressMatches('|/user/reset/|');
    $this->submitForm([], 'Log in');
    $assert_session->addressMatches('/\/user\/\d+\/edit/');
    $this->submitForm([
      'pass[pass1]' => 'my password',
      'pass[pass2]' => 'my password',
    ], 'Save');
  }

}
