<?php

namespace Drupal\Tests\civictheme\Functional;

/**
 * Class CivicthemeInstallOptionalTest.
 *
 * Tests the installation of the CivicTheme with required and optional
 * dependencies.
 *
 * @group civictheme:functional
 * @group site:functional
 */
class CivicthemeInstallOptionalTest extends CivicthemeBrowserTestBase {

  /**
   * Test that a theme can be installed.
   */
  public function testThemeInstall() {
    $adminUser = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($adminUser);
  }

}
