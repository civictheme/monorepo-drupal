<?php

declare(strict_types=1);

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

    // With DB_TLS_ENABLED unset, settings.php autodetects the RDS CA bundle
    // the Salsa Hosting base image ships — so the expected pdo options depend
    // on where the suite runs (bundle present in the app container, absent on
    // a bare host).
    if (!isset($vars['DB_TLS_ENABLED']) && file_exists(self::RDS_CERT_PATH)) {
      $expected['default']['default']['pdo'] = [
        \PDO::MYSQL_ATTR_SSL_CA => self::RDS_CERT_PATH,
        \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => TRUE,
      ];
    }

    $this->assertEquals($expected, $this->databases);
  }

  /**
   * The RDS CA bundle path settings.php probes for TLS autodetection.
   */
  const RDS_CERT_PATH = '/opt/rds-ca-certs/rds-ca-cert-bundle.pem';

  /**
   * Data provider for resulting database settings.
   */
  public static function dataProviderDatabases(): array {
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
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' => '',
              'charset' => 'utf8mb4',
              'collation' => 'utf8mb4_general_ci',
              'pdo' => [],
            ],
          ],
        ],
      ],

      [
        [
          'DB_DATABASE' => 'test_db_name',
          'DB_USERNAME' => 'test_db_user',
          'DB_PASSWORD' => 'test_db_pass',
          'DB_HOST' => 'test_db_host',
          'DB_PORT' => 'test_db_port',
          'DB_PREFIX' => 'test_db_prefix',
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
              'prefix' => 'test_db_prefix',
              'charset' => 'utf8mb4',
              'collation' => 'utf8mb4_general_ci',
              'pdo' => [],
            ],
          ],
        ],
      ],

      // Local development with MySQL 8 — TLS explicitly disabled.
      [
        [
          'DB_TLS_ENABLED' => '0',
        ],
        [
          'default' => [
            'default' => [
              'database' => 'drupal',
              'username' => 'drupal',
              'password' => 'drupal',
              'host' => 'localhost',
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' => '',
              'charset' => 'utf8mb4',
              'collation' => 'utf8mb4_general_ci',
              'pdo' => [
                \PDO::MYSQL_ATTR_SSL_CA => '',
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => FALSE,
              ],
            ],
          ],
        ],
      ],
    ];
  }

}
