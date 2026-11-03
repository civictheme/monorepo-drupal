<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme_starter\Functional;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Class CivicthemeInstallRequiredTest.php.
 *
 * Tests that CivicTheme installs standalone, without the content model
 * recipes applied first.
 *
 * @group civictheme:functional
 * @group site:functional
 */
#[Group('civictheme:functional')]
#[Group('site:functional')]
#[RunTestsInSeparateProcesses]
class CivicthemeInstallRequiredTest extends CivicthemeBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $applyRecipes = FALSE;

  /**
   * Test that a theme can be installed.
   */
  public function testThemeInstall(): void {
    $adminUser = $this->drupalCreateUser(['administer site configuration']);
    // @phpstan-ignore-next-line
    $this->drupalLogin($adminUser);
  }

}
