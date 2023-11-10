<?php

namespace Drupal\Tests\civictheme\Functional;

/**
 * Class CivicthemeInstallOptionalTest.php.
 *
 * Tests the installation of the CivicTheme with only required dependencies.
 *
 * @group civictheme:functional
 * @group site:functional
 */
class CivicthemeInstallRequiredTest extends CivicthemeBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $optionalDependencies = FALSE;

  /**
   * Test that a theme can be installed.
   */
  public function testThemeInstall(): void {
    $adminUser = $this->drupalCreateUser(['administer site configuration']);
    // @phpstan-ignore-next-line
    $this->drupalLogin($adminUser);
  }

}
