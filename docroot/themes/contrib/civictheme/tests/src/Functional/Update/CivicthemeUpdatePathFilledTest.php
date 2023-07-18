<?php

namespace Drupal\Tests\civictheme\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Tests the hook_post_update_NAME() implementations in filled database.
 *
 * @group civictheme:functional:update
 * @group site:functional
 */
class CivicthemeUpdatePathFilledTest extends UpdatePathTestBase {

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
    'block.block.civictheme_footer_acknowledgment_of_country',
    'block.block.civictheme_footer_copyright',
    'block.block.civictheme_footer_social_links',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->container->get('module_installer')->install(['sqlite']);
  }

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../fixtures/updates/drupal_10.0.0-rc1.minimal.civictheme_1.3.2.filled.php.gz',
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

    // Assertions for civictheme_post_update_vertical_spacing().
    $this->assertSession()->pageTextContains('Update vertical_spacing');
    $this->assertSession()->pageTextContains('Update results ran');
    $this->assertSession()->pageTextContains('Processed: 155');
    $this->assertSession()->pageTextContains('Updated: 9');
    $this->assertSession()->pageTextContains('Skipped: 146');

    // Assertions for civictheme_post_update_rename_list_fields().
    $this->assertSession()->pageTextContains('Update rename_list_fields');
    $this->assertSession()->pageTextContains("Content from field 'field_c_p_column_count' was moved to 'field_c_p_list_column_count'");
    $this->assertSession()->pageTextContains("Content from field 'field_c_p_fill_width' was moved to 'field_c_p_list_fill_width'.");
    $this->assertSession()->pageTextContains('Update results ran');
    $this->assertSession()->pageTextContains('Processed: 24');
    $this->assertSession()->pageTextContains('Updated: 24');

    // Assertions for civictheme_post_update_replace_summary().
    $this->assertSession()->pageTextContains('Update replace_summary');
    $this->assertSession()->pageTextContains("Content from field 'field_c_p_summary' was moved to 'field_c_p_content'");
    $this->assertSession()->pageTextContains('Update results ran');
    $this->assertSession()->pageTextContains('Processed: 48');
    $this->assertSession()->pageTextContains('Updated: 39');
    $this->assertSession()->pageTextContains('Skipped: 9');

    // Assertions for civictheme_post_update_replace_date().
    $this->assertSession()->pageTextContains('Update replace_date');
    $this->assertSession()->pageTextContains("Content from field 'field_c_n_date' was moved to 'field_c_n_date_range'");
    $this->assertSession()->pageTextContains('Update results ran');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');
  }

}
