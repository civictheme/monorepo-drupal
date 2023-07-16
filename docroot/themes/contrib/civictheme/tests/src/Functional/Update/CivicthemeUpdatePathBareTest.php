<?php

namespace Drupal\Tests\civictheme\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Tests the hook_post_update_NAME() implementations on bare database.
 *
 * @group civictheme:functional:update
 * @group site:functional
 */
class CivicthemeUpdatePathBareTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $configSchemaCheckerExclusions = [
    // Exclude "broken" schemas provided in the database dumps. When a new
    // version is released - the dump is updated and schemas are fixed - review
    // this list and remove items.
    'civictheme.settings',
    'core.entity_form_display.node.civictheme_event.default',
    'core.entity_form_display.node.civictheme_page.default',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../fixtures/updates/drupal_10.0.0-rc1.minimal.civictheme_1.3.2.bare.php.gz',
    ];
  }

  /**
   * Tests that the database was properly loaded.
   *
   * This is a smoke test for the hook_update_N() CivicTheme test system itself.
   */
  public function testDatabaseLoaded() {
    $this->assertEquals('minimal', \Drupal::config('core.extension')->get('profile'));
    // Ensure that a user can be created and do a basic test that
    // the site is available by logging in.
    $this->drupalLogin($this->createUser(admin: TRUE));
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests updates.
   */
  public function testUpdates() {
    $this->runUpdates();
  }

}
