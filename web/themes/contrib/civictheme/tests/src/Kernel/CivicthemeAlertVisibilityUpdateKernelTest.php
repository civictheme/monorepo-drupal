<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the alert REST export visibility post-update.
 *
 * @group CivicTheme
 * @group site:kernel
 */
class CivicthemeAlertVisibilityUpdateKernelTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'serialization', 'rest', 'views'];

  /**
   * {@inheritdoc}
   */
  protected static $configSchemaCheckerExclusions = ['views.view.civictheme_alerts'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    require_once __DIR__ . '/../../../civictheme.post_update.php';
  }

  /**
   * Tests the post-update preserves the rest of the REST export configuration.
   */
  public function testRawOutputIsEnabledAndIdempotent(): void {
    $config = $this->config('views.view.civictheme_alerts');
    $config->set('display.rest_export_civictheme_alerts.display_options.row.options.field_options', [
      'rendered_entity' => [
        'alias' => 'message',
        'raw_output' => FALSE,
      ],
      'field_c_n_alert_page_visibility' => [
        'alias' => 'visibility',
        'raw_output' => FALSE,
      ],
    ])->save();

    $message = civictheme_post_update_alert_visibility_raw_output();

    $this->assertStringContainsString('Updated alert REST export', $message);
    $this->assertTrue($this->config('views.view.civictheme_alerts')->get('display.rest_export_civictheme_alerts.display_options.row.options.field_options.field_c_n_alert_page_visibility.raw_output'));
    $this->assertFalse($this->config('views.view.civictheme_alerts')->get('display.rest_export_civictheme_alerts.display_options.row.options.field_options.rendered_entity.raw_output'));

    $message = civictheme_post_update_alert_visibility_raw_output();

    $this->assertStringContainsString('already uses raw visibility', $message);
  }

  /**
   * Tests the post-update does not create incomplete REST export configuration.
   */
  public function testMissingVisibilityFieldIsSkipped(): void {
    $config = $this->config('views.view.civictheme_alerts');
    $config->set('display.rest_export_civictheme_alerts.display_options.row.options.field_options', [
      'rendered_entity' => [
        'alias' => 'message',
        'raw_output' => FALSE,
      ],
    ])->save();

    $message = civictheme_post_update_alert_visibility_raw_output();

    $this->assertStringContainsString('skipped because the visibility field does not exist', $message);
    $this->assertNull($this->config('views.view.civictheme_alerts')->get('display.rest_export_civictheme_alerts.display_options.row.options.field_options.field_c_n_alert_page_visibility'));
  }

  /**
   * Tests the post-update does not create a missing view configuration.
   */
  public function testMissingViewIsSkipped(): void {
    $message = civictheme_post_update_alert_visibility_raw_output();

    $this->assertStringContainsString('skipped because the visibility field does not exist', $message);
    $this->assertSame([], \Drupal::service('config.storage')->listAll('views.view.civictheme_alerts'));
  }

}
