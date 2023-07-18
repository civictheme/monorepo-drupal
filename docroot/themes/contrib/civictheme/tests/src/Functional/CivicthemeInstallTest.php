<?php

namespace Drupal\Tests\civictheme\Functional;

/**
 * Class CivicthemeInstallTest.
 *
 * Tests the installation of the Civictheme.
 *
 * @group civictheme:functional
 * @group site:functional
 */
class CivicthemeInstallTest extends CivicthemeBrowserTestBase {

  /**
   * Test that a theme can be installed.
   */
  public function testThemeInstall() {
    $adminUser = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($adminUser);
  }

}
