<?php

namespace Drupal;

/**
 * Class DatabaseSettingsTest.
 *
 * Tests for Drupal database settings.
 *
 * @group drupal_settings
 */
class DatabaseSettingsTest extends SettingsTestCase {

  /**
   * Test resulting database settings based on environment variables.
   *
   * @dataProvider dataProviderDatabases
   */
  public function testDatabases(array $vars, array $expected): void {
    $this->setEnvVars($vars);

    $this->requireSettingsFile();

    $this->assertEquals($expected, $this->databases);
  }

  /**
   * Data provider for resulting database settings.
   */
  public function dataProviderDatabases(): array {
    return [
      [
        [],
        [
          'default' => [
            'default' => [
              'database' => 'drupal',
              'username' => 'drupal',
              'password' => 'drupal',
              'host' => 'localhost',
              'port' => '',
              'driver' => 'mysql',
              'prefix' => '',
            ],
          ],
        ],
      ],

      [
        [
          'MARIADB_DATABASE' => 'test_db_name',
          'MARIADB_USERNAME' => 'test_db_user',
          'MARIADB_PASSWORD' => 'test_db_pass',
          'MARIADB_HOST' => 'test_db_host',
          'MARIADB_PORT' => 'test_db_port',
        ],
        [
          'default' => [
            'default' => [
              'database' => 'test_db_name',
              'username' => 'test_db_user',
              'password' => 'test_db_pass',
              'host' => 'test_db_host',
              'port' => 'test_db_port',
              'driver' => 'mysql',
              'prefix' => '',
            ],
          ],
        ],
      ],
    ];
  }

}
