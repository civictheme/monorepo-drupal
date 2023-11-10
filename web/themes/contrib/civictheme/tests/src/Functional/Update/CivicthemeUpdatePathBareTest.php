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
    'simple_sitemap.custom_links.default',
    'simple_sitemap.custom_links.index',
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
  protected function setDatabaseDumpFiles(): void {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../fixtures/updates/drupal_10.0.0-rc1.minimal.civictheme_1.3.2.bare.php.gz',
    ];
  }

  /**
   * Tests that the database was properly loaded.
   *
   * This is a smoke test for the hook_update_N() CivicTheme test system itself.
   */
  public function testDatabaseLoaded(): void {
    $this->assertEquals('minimal', \Drupal::config('core.extension')->get('profile'));
    // Ensure that a user can be created and do a basic test that
    // the site is available by logging in.
    $this->drupalLogin($this->createUser(admin: TRUE));
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests updates.
   */
  public function testUpdates(): void {
    $this->runUpdates();

    $this->assertSession()->pageTextContains('Update rename_block_banner_blend_mode');
    $this->assertSession()->pageTextContains("Content from field 'field_c_b_blend_mode' was moved to 'field_c_b_banner_blend_mode'.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');

    $this->assertSession()->pageTextContains('Update rename_event_date_field');
    $this->assertSession()->pageTextContains("Content from field 'field_c_n_date' was moved to 'field_c_n_date_range'. The 'field_c_n_date_range' field was removed from 'civictheme_event' node type.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');

    $this->assertSession()->pageTextContains('Update rename_list_fields');
    $this->assertSession()->pageTextContains("Content from field 'field_c_p_column_count' was moved to 'field_c_p_list_column_count'. Content from field 'field_c_p_fill_width' was moved to 'field_c_p_list_fill_width'.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');

    $this->assertSession()->pageTextContains('Update rename_node_banner_blend_mode');
    $this->assertSession()->pageTextContains("Content from field 'field_c_n_blend_mode' was moved to 'field_c_n_banner_blend_mode'.");
    $this->assertSession()->pageTextContains("The 'field_c_n_blend_mode' field was removed from 'civictheme_page' node type.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');

    $this->assertSession()->pageTextContains('Update replace_summary_field');
    $this->assertSession()->pageTextContains("Content from field 'field_c_p_summary' was moved to 'field_c_p_content'. The 'field_c_p_summary' field was removed from civictheme_attachment, civictheme_callout, civictheme_next_step, civictheme_promo paragraph types.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');

    $this->assertSession()->pageTextContains('Update set_vertical_spacing_empty_value');
    $this->assertSession()->pageTextContains("Updated values for fields 'field_c_n_vertical_spacing' and 'field_c_p_vertical_spacing'.");
    $this->assertSession()->pageTextContains('Update ran in');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Processed: 0');
    $this->assertSession()->pageTextContains('Updated: 0');
  }

}
