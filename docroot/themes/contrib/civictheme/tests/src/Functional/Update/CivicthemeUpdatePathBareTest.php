<?php

namespace Drupal\Tests\civictheme\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Tests the hook_post_update_NAME() implementations on bare database.
 *
 * How it works:
 *
 * The update tests are different from the other tests in a way that we are not
 * testing each update separately but rather run all of them at once using a
 * prepared fixture database.
 *
 * It's crucial to execute update tests on both empty and populated databases
 * to confirm the correct operation of the update path. As all updates are
 * executed within a single test, the test cases for both types of databases
 * are identical. This testing is facilitated via CivicthemeUpdatePathBareTest
 * and CivicthemeUpdatePathFilledTest classes (the latter extends the former
 * and merely overwrites the database file).
 *
 * UpdatePathTestBase test class provides all necessary wiring to install a site
 * from the provided fixture database dump set in setDatabaseDumpFiles().
 *
 * Updating fixture database files:
 * 1. Checkout this repository at the specific release
 * 2. Update bare database dump:
 *    @code
 *    DREVOPS_DRUPAL_VERSION=10 DREVOPS_DRUPAL_PROFILE=minimal CIVICTHEME_SKIP_SUBTHEME_ACTIVATION=1 CIVICTHEME_SKIP_LIBRARY_INSTALL=1 SKIP_GENERATED_CONTENT_CREATE=1 ahoy build
 *    ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_<Drupal-Version>.minimal.civictheme_<CivicTheme-Version>.bare.php.gz
 *    @endcode
 * 3. Update filled database dump:
 *    @code
 *    DREVOPS_DRUPAL_VERSION=10 DREVOPS_DRUPAL_PROFILE=minimal CIVICTHEME_SKIP_SUBTHEME_ACTIVATION=1 CIVICTHEME_SKIP_LIBRARY_INSTALL=1 ahoy build
 *    ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_<Drupal-Version>.minimal.civictheme_<CivicTheme-Version>.filled.php.gz
 *    @endcode
 *
 * @group civictheme:functional:update
 */
class CivicthemeUpdatePathBareTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

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

}
