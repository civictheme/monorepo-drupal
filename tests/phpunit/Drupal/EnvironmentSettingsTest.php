<?php

declare(strict_types=1);

namespace Drupal;

/**
 * Class EnvironmentSettingsTest.
 *
 * Settings and configs within tests are sorted alphabetically.
 *
 * The main purpose of these tests is to ensure that the settings and configs
 * appear in every environment as expected.
 *
 * @group drupal_settings
 *
 * phpcs:disable Squiz.WhiteSpace.FunctionSpacing.Before
 * phpcs:disable Squiz.WhiteSpace.FunctionSpacing.After
 * phpcs:disable Squiz.WhiteSpace.FunctionSpacing.AfterLast
 * phpcs:disable Drupal.Classes.ClassDeclaration.CloseBraceAfterBody
 */
class EnvironmentSettingsTest extends SettingsTestCase {

  /**
   * Test the resulting environment based on the provider's configuration.
   *
   * @dataProvider dataProviderEnvironmentTypeResolution
   */
  public function testEnvironmentTypeResolution(array $vars, string $expected_env): void {
    $this->setEnvVars($vars);

    $this->requireSettingsFile();

    $this->assertEquals($expected_env, $this->settings['environment']);
  }

  /**
   * Data provider for testing of the resulting environment.
   */
  public function dataProviderEnvironmentTypeResolution(): array {
    $this->requireSettingsFile();

    return [
      // By default, the default environment type is local.
      [[], static::ENVIRONMENT_LOCAL],

      // CI.
      [
        [
          'CI' => 1,
        ],
        static::ENVIRONMENT_CI,
      ],

      // Lagoon.
      [
        [
          'LAGOON' => 1,
        ],
        static::ENVIRONMENT_LOCAL,
      ],

      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'production',
        ],
        static::ENVIRONMENT_PROD,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_GIT_BRANCH' => 'main',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => 'main',
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
        ],
        static::ENVIRONMENT_PROD,
      ],

      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'release',
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'release/1.2.3',
        ],
        static::ENVIRONMENT_TEST,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'hotfix',
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'hotfix/1.2.3',
        ],
        static::ENVIRONMENT_TEST,
      ],

      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => FALSE,
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => FALSE,
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => FALSE,
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => FALSE,
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'somebranch',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => FALSE,
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => FALSE,
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => 'otherbranch',
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'somebranch',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => 'otherbranch',
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => '',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => '',
        ],
        static::ENVIRONMENT_DEV,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
          'LAGOON_GIT_BRANCH' => 'somebranch',
          'DREVOPS_LAGOON_PRODUCTION_BRANCH' => 'somebranch',
        ],
        static::ENVIRONMENT_PROD,
      ],
      [
        [
          'LAGOON' => 1,
          'LAGOON_ENVIRONMENT_TYPE' => 'development',
        ],
        static::ENVIRONMENT_DEV,
      ],
    ];
  }

  /**
   * Test generic settings without any environment overrides.
   */
  public function testEnvironmentGeneric(): void {
    $this->setEnvVars([
      'DRUPAL_ENVIRONMENT' => static::ENVIRONMENT_SUT,
    ]);

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#006600';
    $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_SUT;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_SUT;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['trusted_host_patterns'] = [
      '^.+\.docker\.amazee\.io$',
      '^nginx$',
    ];
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for LOCAL environment.
   */
  public function testEnvironmentLocal(): void {
    $this->setEnvVars([
      'DRUPAL_ENVIRONMENT' => static::ENVIRONMENT_LOCAL,
    ]);

    $this->requireSettingsFile();

    $config['automated_cron.settings']['interval'] = 0;
    $config['environment_indicator.indicator']['bg_color'] = '#006600';
    $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_LOCAL;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = FALSE;
    $config['system.logging']['error_level'] = 'all';
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_LOCAL;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['skip_permissions_hardening'] = TRUE;
    $settings['trusted_host_patterns'] = [
      '^.+\.docker\.amazee\.io$',
      '^nginx$',
    ];
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for CI environment.
   */
  public function testEnvironmentCi(): void {
    $this->setEnvVars([
      'CI' => TRUE,
    ]);

    $this->requireSettingsFile();

    $config['automated_cron.settings']['interval'] = 0;
    $config['environment_indicator.indicator']['bg_color'] = '#006600';
    $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_CI;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = FALSE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_CI;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['skip_permissions_hardening'] = TRUE;
    $settings['suspend_mail_send'] = TRUE;
    $settings['trusted_host_patterns'] = [
      '^.+\.docker\.amazee\.io$',
      '^nginx$',
    ];

    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for dynamic environment.
   */
  public function testEnvironmentLagoonDynamic(): void {
    $this->setEnvVars([
      'LAGOON' => 1,
      'LAGOON_ENVIRONMENT_TYPE' => 'development',
      'LAGOON_ROUTES' => 'http://example1.com,https://example2/com',
      'LAGOON_PROJECT' => 'test_project',
      'LAGOON_GIT_BRANCH' => 'test_branch',
      'LAGOON_GIT_SAFE_BRANCH' => 'test_branch',
    ]);

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#4caf50';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_DEV;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['cache_prefix']['default'] = 'test_project_test_branch';
    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_DEV;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['reverse_proxy'] = TRUE;
    $settings['reverse_proxy_header'] = 'HTTP_TRUE_CLIENT_IP';
    $settings['trusted_host_patterns'][] = '^.+\.docker\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^nginx$';
    $settings['trusted_host_patterns'][] = '^nginx\-php$';
    $settings['trusted_host_patterns'][] = '^.+\.au\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^example1\.com|example2/com$';
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Dev environment.
   */
  public function testEnvironmentLagoonDev(): void {
    $this->setEnvVars([
      'LAGOON' => 1,
      'LAGOON_ENVIRONMENT_TYPE' => 'development',
      'LAGOON_ROUTES' => 'http://example1.com,https://example2/com',
      'LAGOON_PROJECT' => 'test_project',
      'LAGOON_GIT_BRANCH' => 'develop',
      'LAGOON_GIT_SAFE_BRANCH' => 'develop',
    ]);

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#4caf50';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_DEV;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['cache_prefix']['default'] = 'test_project_develop';
    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_DEV;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['reverse_proxy'] = TRUE;
    $settings['reverse_proxy_header'] = 'HTTP_TRUE_CLIENT_IP';
    $settings['trusted_host_patterns'][] = '^.+\.docker\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^nginx$';
    $settings['trusted_host_patterns'][] = '^nginx\-php$';
    $settings['trusted_host_patterns'][] = '^.+\.au\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^example1\.com|example2/com$';
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Test environment.
   */
  public function testEnvironmentLagoonTest(): void {
    $this->setEnvVars([
      'LAGOON' => 1,
      'LAGOON_ENVIRONMENT_TYPE' => 'development',
      'LAGOON_ROUTES' => 'http://example1.com,https://example2/com',
      'LAGOON_PROJECT' => 'test_project',
      'LAGOON_GIT_BRANCH' => 'master',
      'LAGOON_GIT_SAFE_BRANCH' => 'master',
    ]);

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#fff176';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_TEST;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['cache_prefix']['default'] = 'test_project_master';
    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_TEST;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['reverse_proxy'] = TRUE;
    $settings['reverse_proxy_header'] = 'HTTP_TRUE_CLIENT_IP';
    $settings['trusted_host_patterns'][] = '^.+\.docker\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^nginx$';
    $settings['trusted_host_patterns'][] = '^nginx\-php$';
    $settings['trusted_host_patterns'][] = '^.+\.au\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^example1\.com|example2/com$';
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Prod environment.
   */
  public function testEnvironmentLagoonProd(): void {
    $this->setEnvVars([
      'LAGOON' => 1,
      'LAGOON_ENVIRONMENT_TYPE' => 'production',
      'LAGOON_ROUTES' => 'http://example1.com,https://example2/com',
      'LAGOON_PROJECT' => 'test_project',
      'LAGOON_GIT_BRANCH' => 'production',
      'LAGOON_GIT_SAFE_BRANCH' => 'production',
      'DREVOPS_LAGOON_PRODUCTION_BRANCH' => 'production',
    ]);

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#ef5350';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_PROD;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = 1;
    $config['system.performance']['js']['preprocess'] = 1;
    $this->assertConfig($config);

    $settings['cache_prefix']['default'] = 'test_project_production';
    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '../config/default';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_PROD;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = hash('sha256', getenv('MARIADB_HOST') ?: 'localhost');
    $settings['reverse_proxy'] = TRUE;
    $settings['reverse_proxy_header'] = 'HTTP_TRUE_CLIENT_IP';
    $settings['trusted_host_patterns'][] = '^.+\.docker\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^nginx$';
    $settings['trusted_host_patterns'][] = '^nginx\-php$';
    $settings['trusted_host_patterns'][] = '^.+\.au\.amazee\.io$';
    $settings['trusted_host_patterns'][] = '^example1\.com|example2/com$';
    $this->assertSettings($settings);
  }

}
