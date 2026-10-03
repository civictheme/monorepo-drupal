<?php

declare(strict_types=1);

namespace Drupal\Tests\civictheme\Unit;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the alert REST pager update without changing other view settings.
 *
 * @group CivicTheme
 */
class CivicthemeAlertPagerUpdateTest extends UnitTestCase {

  /**
   * Tests existing views and repeat execution of the update.
   */
  public function testExistingView(): void {
    $data = Yaml::parseFile(__DIR__ . '/../../../config/install/views.view.civictheme_alerts.yml');
    // Simulate the previous release, where REST defaults to a limited pager.
    unset($data['display']['rest_export_civictheme_alerts']['display_options']['pager']);
    $expected = $data;
    $expected['display']['rest_export_civictheme_alerts']['display_options']['pager'] = [
      'type' => 'none',
      'options' => ['offset' => 0],
    ];
    $config = $this->prepareConfig($data);
    $config->expects($this->exactly(2))->method('save')->willReturnSelf();

    civictheme_post_update_remove_alert_export_limit();
    $this->assertSame($expected, $config->getRawData());
    civictheme_post_update_remove_alert_export_limit();
    $this->assertSame($expected, $config->getRawData());
  }

  /**
   * Tests that a removed REST display is not recreated.
   */
  public function testMissingDisplay(): void {
    $data = ['display' => ['default' => ['display_plugin' => 'default']]];
    $config = $this->prepareConfig($data);
    $config->expects($this->never())->method('save');
    civictheme_post_update_remove_alert_export_limit();
    $this->assertSame($data, $config->getRawData());
  }

  /**
   * Tests that an absent view is not created.
   */
  public function testMissingView(): void {
    $config = $this->prepareConfig([], TRUE);
    $config->expects($this->never())->method('save');
    civictheme_post_update_remove_alert_export_limit();
    $this->assertSame([], $config->getRawData());
  }

  /**
   * Prepares real config data access with persistence mocked out.
   */
  protected function prepareConfig(array $data, bool $is_new = FALSE): Config {
    require_once __DIR__ . '/../../../civictheme.post_update.php';
    $config = $this->getMockBuilder(Config::class)
      ->disableOriginalConstructor()
      ->onlyMethods(['save', 'isNew'])
      ->getMock();
    $config->setData($data);
    $config->method('isNew')->willReturn($is_new);
    $factory = $this->createMock(ConfigFactoryInterface::class);
    $factory->method('getEditable')->with('views.view.civictheme_alerts')->willReturn($config);
    $container = new ContainerBuilder();
    $container->set('config.factory', $factory);
    $container->set('string_translation', $this->getStringTranslationStub());
    \Drupal::setContainer($container);
    return $config;
  }

}
