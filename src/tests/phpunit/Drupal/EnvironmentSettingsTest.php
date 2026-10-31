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
    // Deployed environment types enforce the presence of DB and hash salt
    // variables; provide them so resolution can be tested in isolation.
    $this->setEnvVars($vars + static::serverRequiredVars());

    $this->requireSettingsFile();

    $this->assertEquals($expected_env, $this->settings['environment']);
  }

  /**
   * Data provider for testing of the resulting environment.
   */
  public static function dataProviderEnvironmentTypeResolution(): array {
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

      // Explicit local on a developer machine (set via .env).
      [
        [
          'QUANT_ENV_TYPE' => 'local',
        ],
        static::ENVIRONMENT_LOCAL,
      ],

      // CI detection wins over an explicit local type.
      [
        [
          'CI' => 1,
          'QUANT_ENV_TYPE' => 'local',
        ],
        static::ENVIRONMENT_CI,
      ],

      // Salsa Hosting / Quant Cloud environment types.
      [
        [
          'QUANT_ENV_TYPE' => 'production',
        ],
        static::ENVIRONMENT_PROD,
      ],
      [
        [
          'QUANT_ENV_TYPE' => 'test',
        ],
        static::ENVIRONMENT_TEST,
      ],
      [
        [
          'QUANT_ENV_TYPE' => 'development',
        ],
        static::ENVIRONMENT_DEV,
      ],

      // Any other deployed environment type is considered development.
      [
        [
          'QUANT_ENV_TYPE' => 'staging',
        ],
        static::ENVIRONMENT_DEV,
      ],

      // Empty type keeps the local default.
      [
        [
          'QUANT_ENV_TYPE' => '',
        ],
        static::ENVIRONMENT_LOCAL,
      ],

      // DRUPAL_ENVIRONMENT overrides everything.
      [
        [
          'QUANT_ENV_TYPE' => 'production',
          'DRUPAL_ENVIRONMENT' => static::ENVIRONMENT_SUT,
        ],
        static::ENVIRONMENT_SUT,
      ],
      [
        [
          'CI' => 1,
          'DRUPAL_ENVIRONMENT' => static::ENVIRONMENT_SUT,
        ],
        static::ENVIRONMENT_SUT,
      ],
    ];
  }

  /**
   * Test generic settings without any environment overrides.
   */
  public function testEnvironmentGeneric(): void {
    $this->setEnvVars([
      'DRUPAL_ENVIRONMENT' => static::ENVIRONMENT_SUT,
    ] + static::serverRequiredVars());

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#006600';
    $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_SUT;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_SUT;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::HASH_SALT_TESTING;
    $settings['trusted_host_patterns'] = static::trustedHostPatternsBase();
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
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_LOCAL;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::derivedHashSalt();
    $settings['skip_permissions_hardening'] = TRUE;
    $settings['trusted_host_patterns'] = array_merge(static::trustedHostPatternsBase(), ['^.+$']);
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
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_CI;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::derivedHashSalt();
    $settings['skip_permissions_hardening'] = TRUE;
    $settings['suspend_mail_send'] = TRUE;
    $settings['trusted_host_patterns'] = array_merge(static::trustedHostPatternsBase(), ['^.+$']);
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Dev environment.
   */
  public function testEnvironmentDev(): void {
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'development',
    ] + static::serverRequiredVars());

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#4caf50';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_DEV;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_DEV;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::HASH_SALT_TESTING;
    $settings['trusted_host_patterns'] = array_merge(static::trustedHostPatternsBase(), ['^.+$']);
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Test environment.
   */
  public function testEnvironmentTestType(): void {
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'test',
    ] + static::serverRequiredVars());

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#fff176';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_TEST;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['shield.settings']['shield_enable'] = TRUE;
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_TEST;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::HASH_SALT_TESTING;
    $settings['trusted_host_patterns'] = static::trustedHostPatternsBase();
    $this->assertSettings($settings);
  }

  /**
   * Test per-environment settings for Prod environment.
   */
  public function testEnvironmentProd(): void {
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'production',
    ] + static::serverRequiredVars());

    $this->requireSettingsFile();

    $config['environment_indicator.indicator']['bg_color'] = '#ef5350';
    $config['environment_indicator.indicator']['fg_color'] = '#000000';
    $config['environment_indicator.indicator']['name'] = static::ENVIRONMENT_PROD;
    $config['environment_indicator.settings']['favicon'] = TRUE;
    $config['environment_indicator.settings']['toolbar_integration'] = [TRUE];
    $config['maintenance_theme'] = 'civictheme';
    $config['system.performance']['cache']['page']['max_age'] = 900;
    $config['system.performance']['css']['preprocess'] = TRUE;
    $config['system.performance']['js']['preprocess'] = TRUE;
    $this->assertConfig($config);

    $settings['config_exclude_modules'] = [];
    $settings['config_sync_directory'] = '/opt/drupal/config/sync';
    $settings['container_yamls'][0] = $this->app_root . '/' . $this->site_path . '/services.yml';
    $settings['entity_update_batch_size'] = 50;
    $settings['environment'] = static::ENVIRONMENT_PROD;
    $settings['file_private_path'] = 'sites/default/files/private';
    $settings['file_public_path'] = 'sites/default/files';
    $settings['file_scan_ignore_directories'] = [
      'node_modules',
      'bower_components',
    ];
    $settings['file_temp_path'] = static::TMP_PATH_TESTING;
    $settings['hash_salt'] = static::HASH_SALT_TESTING;
    $settings['trusted_host_patterns'] = static::trustedHostPatternsBase();
    $this->assertSettings($settings);
  }

  /**
   * Test that production refuses to boot without a hash salt.
   */
  public function testProductionRequiresHashSalt(): void {
    $vars = static::serverRequiredVars();
    unset($vars['DRUPAL_HASH_SALT']);
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'production',
      'DRUPAL_HASH_SALT' => NULL,
    ] + $vars);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessageMatches('/DRUPAL_HASH_SALT/');

    $this->requireSettingsFile();
  }

  /**
   * Test deployed non-production environments derive a hash salt from DB vars.
   *
   * @dataProvider dataProviderNonProductionDerivesHashSalt
   */
  public function testNonProductionDerivesHashSalt(string $env_type): void {
    $vars = static::serverRequiredVars();
    unset($vars['DRUPAL_HASH_SALT']);
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => $env_type,
      'DRUPAL_HASH_SALT' => NULL,
    ] + $vars);

    $this->requireSettingsFile();

    $this->assertEquals(static::derivedServerHashSalt(), $this->settings['hash_salt']);
  }

  /**
   * Data provider for testNonProductionDerivesHashSalt.
   */
  public static function dataProviderNonProductionDerivesHashSalt(): array {
    return [
      ['development'],
      ['test'],
    ];
  }

  /**
   * Test that a deployed environment refuses to boot without DB variables.
   */
  public function testServerRequiresDatabaseVars(): void {
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'production',
      'DRUPAL_HASH_SALT' => static::HASH_SALT_TESTING,
    ]);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessageMatches('/DB_/');

    $this->requireSettingsFile();
  }

  /**
   * Test DRUPAL_TRUSTED_HOSTS adds patterns.
   */
  public function testTrustedHostsFromEnv(): void {
    $this->setEnvVars([
      'QUANT_ENV_TYPE' => 'production',
      'DRUPAL_TRUSTED_HOSTS' => '^www\.example\.com$, ^example\.com$',
    ] + static::serverRequiredVars());

    $this->requireSettingsFile();

    $this->assertEquals(
      array_merge(static::trustedHostPatternsBase(), ['^www\.example\.com$', '^example\.com$']),
      $this->settings['trusted_host_patterns']
    );
  }

}
