<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme_starter\Functional;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Class CivicthemeInstallOptionalTest.
 *
 * Tests the installation of the CivicTheme on a site with the full content
 * model applied from the recipes.
 *
 * @group civictheme:functional
 * @group site:functional
 */
#[Group('civictheme:functional')]
#[Group('site:functional')]
#[RunTestsInSeparateProcesses]
class CivicthemeInstallOptionalTest extends CivicthemeBrowserTestBase {

  /**
   * Test that a theme can be installed.
   */
  public function testThemeInstall(): void {
    $adminUser = $this->drupalCreateUser(['administer site configuration']);
    // @phpstan-ignore-next-line
    $this->drupalLogin($adminUser);
  }

}
