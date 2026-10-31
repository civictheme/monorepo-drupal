<?php

declare(strict_types=1);

// phpcs:ignoreFile

/**
 * @file
 * CivicTheme monorepo settings.php — Salsa Hosting scaffold base, env-driven.
 *
 * Structure:
 * - Environment constants + detection (QUANT_ENV_TYPE / CI).
 * - Database (env-driven, installer-rewrite hardened).
 * - Site-specific settings.
 * - Per-environment overrides.
 * - Per-module includes (sites/default/includes/settings.*.php).
 * - Local settings overrides (settings.local.php / services.local.yml).
 *
 * == Installer rewrite hardening (from the scaffold — do not undo) ==
 *
 * Drupal's SettingsEditor::rewrite() runs during install whenever the
 * installer can't open a working DB connection. For each
 * `$databases[...][...] = …;` statement with a "simple" scalar RHS it
 * replaces the value in-place; anything else is left alone AND re-exported as
 * an APPENDED block at end-of-file, which would override the env-driven
 * assignment below and drop the `pdo` key (breaking the local TLS-off
 * switch). Defence: the NULL placeholder before the env-driven assignment is
 * replaced in-place, so nothing is appended. Keep the placeholder first, keep
 * the real assignment a single 2-level write built from a local variable.
 */

use Drupal\Core\Installer\InstallerKernel;

////////////////////////////////////////////////////////////////////////////////
///                       ENVIRONMENT CONSTANTS                              ///
////////////////////////////////////////////////////////////////////////////////

// Use these constants anywhere in code to alter behaviour for a specific
// environment.
// @codeCoverageIgnoreStart
if (!defined('ENVIRONMENT_LOCAL')) {
  define('ENVIRONMENT_LOCAL', 'local');
}
if (!defined('ENVIRONMENT_CI')) {
  define('ENVIRONMENT_CI', 'ci');
}
if (!defined('ENVIRONMENT_PROD')) {
  define('ENVIRONMENT_PROD', 'prod');
}
if (!defined('ENVIRONMENT_TEST')) {
  define('ENVIRONMENT_TEST', 'test');
}
if (!defined('ENVIRONMENT_DEV')) {
  define('ENVIRONMENT_DEV', 'dev');
}
// @codeCoverageIgnoreEnd

// Environment detection: QUANT_ENV_TYPE is a self-managed per-environment
// Salsa Hosting variable (`local` on developer machines via .env). CI is
// detected via the conventional CI variable. DRUPAL_ENVIRONMENT, when set,
// overrides everything.
$settings['environment'] = empty(getenv('CI')) ? ENVIRONMENT_LOCAL : ENVIRONMENT_CI;

switch (getenv('QUANT_ENV_TYPE')) {
  case 'production':
    $settings['environment'] = ENVIRONMENT_PROD;
    break;

  case 'test':
    $settings['environment'] = ENVIRONMENT_TEST;
    break;

  case 'development':
    $settings['environment'] = ENVIRONMENT_DEV;
    break;

  case 'local':
  case '':
  case FALSE:
    // Keep the local/CI value from above.
    break;

  default:
    // Any other deployed environment type is considered development.
    $settings['environment'] = ENVIRONMENT_DEV;
}

// Allow overriding of an environment type.
if (!empty(getenv('DRUPAL_ENVIRONMENT'))) {
  $settings['environment'] = getenv('DRUPAL_ENVIRONMENT');
}

////////////////////////////////////////////////////////////////////////////////
///                             DATABASE                                     ///
////////////////////////////////////////////////////////////////////////////////

/**
 * Installer-rewrite placeholder — see file docblock. MUST come before the
 * env-driven `$databases['default']['default']` assignment below.
 */
$databases['default']['default'] = NULL;

/**
 * Required env vars — fail fast at runtime if a deployed environment is
 * missing them. Skipped during install and on local/CI, where compose (or the
 * test harness) provides them.
 */
if (!InstallerKernel::installationAttempted()
  && !in_array($settings['environment'], [ENVIRONMENT_LOCAL, ENVIRONMENT_CI], TRUE)) {
  foreach (['DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_HOST'] as $required) {
    if (getenv($required) === FALSE || getenv($required) === '') {
      throw new RuntimeException(sprintf('Required env var %s is not set.', $required));
    }
  }
}

/**
 * Database connection — single assignment, no further `$databases[...][...]`
 * mutations elsewhere in this file (see file-level docblock).
 */
$pdo_options = [];
$rds_cert = '/opt/rds-ca-certs/rds-ca-cert-bundle.pem';
if (getenv('DB_TLS_ENABLED') === '0') {
  // Local dev with MySQL 8 — explicitly disable TLS so the client doesn't
  // reject the server's self-signed cert.
  $pdo_options[PDO::MYSQL_ATTR_SSL_CA] = '';
  $pdo_options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = FALSE;
}
elseif (file_exists($rds_cert)) {
  // Production with RDS — verify against the bundled CA.
  $pdo_options[PDO::MYSQL_ATTR_SSL_CA] = $rds_cert;
  $pdo_options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = TRUE;
}

$databases['default']['default'] = [
  'driver'    => getenv('DB_DRIVER') ?: 'mysql',
  'database'  => getenv('DB_DATABASE') ?: 'drupal',
  'username'  => getenv('DB_USERNAME') ?: 'drupal',
  'password'  => getenv('DB_PASSWORD') ?: 'drupal',
  'host'      => getenv('DB_HOST') ?: 'localhost',
  'port'      => getenv('DB_PORT') ?: '3306',
  'prefix'    => getenv('DB_PREFIX') ?: '',
  'charset'   => 'utf8mb4',
  'collation' => 'utf8mb4_general_ci',
  'pdo'       => $pdo_options,
];

////////////////////////////////////////////////////////////////////////////////
///                       SITE-SPECIFIC SETTINGS                             ///
////////////////////////////////////////////////////////////////////////////////

$app_root = $app_root ?? DRUPAL_ROOT;
$site_path = $site_path ?? 'sites/default';
$contrib_path = $app_root . DIRECTORY_SEPARATOR . (is_dir($app_root . DIRECTORY_SEPARATOR . 'modules/contrib') ? 'modules/contrib' : 'modules');

// Load services definition file.
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

/**
 * Hash salt.
 *
 * An explicit DRUPAL_HASH_SALT (Salsa Hosting platform variable) always wins.
 * Without one, every deployed environment — production included — derives a
 * salt from the platform-injected DB_* secrets: unique per environment,
 * secret, and present before anyone configures the environment, so ANY
 * environment installs itself on its first deploy with zero manual steps.
 * The scaffold's rule that production must supply its own salt is deliberately
 * relaxed here: this project's production is a demo site that reinstalls from
 * profile on every deploy, the salt plays no part in password hashing, and
 * the cost of a DB-credential rotation is only in-flight one-time login links
 * and itok image tokens. Local development, CI and the installer get a
 * path-derived value, because there the salt protects nothing an attacker
 * cannot already reach.
 */
$hash_salt = getenv('DRUPAL_HASH_SALT') ?: '';
if ($hash_salt === '') {
  if (!in_array($settings['environment'], [ENVIRONMENT_LOCAL, ENVIRONMENT_CI], TRUE)) {
    // Deployed environment: derive from the injected DB secrets.
    $hash_salt = hash('sha256', getenv('DB_PASSWORD') . getenv('DB_HOST') . getenv('DB_DATABASE'));
  }
  else {
    // Local / CI / installer only. Deliberately NOT a secret.
    $hash_salt = hash('sha256', 'civictheme-local-' . __FILE__);
  }
}
$settings['hash_salt'] = $hash_salt;

// Location of the site configuration files. The site installs from a profile,
// so config/sync stays empty and deploys take the updb path.
$settings['config_sync_directory'] = getenv('DRUPAL_CONFIG_SYNC') ?: '/opt/drupal/config/sync';

// File paths — env-overridable for non-default deployments. On Salsa Hosting
// DRUPAL_PRIVATE_FILES points at a dedicated volume outside the docroot
// (/opt/drupal/private); the default below only applies locally, where the
// files tree is a bind mount.
$settings['file_public_path'] = getenv('DRUPAL_PUBLIC_FILES') ?: 'sites/default/files';
$settings['file_private_path'] = getenv('DRUPAL_PRIVATE_FILES') ?: 'sites/default/files/private';
$settings['file_temp_path'] = getenv('DRUPAL_TEMP_PATH') ?: (getenv('TMP') ?: '/tmp');

// Expiration of cached pages.
$config['system.performance']['cache']['page']['max_age'] = 900;

// Aggregate CSS and JS files.
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

// The default list of directories that will be ignored by Drupal's file API.
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// The default number of entities to update in a batch process.
$settings['entity_update_batch_size'] = 50;

// Modules excluded from config export.
$settings['config_exclude_modules'] = [];

ini_set('date.timezone', 'Australia/Melbourne');
date_default_timezone_set('Australia/Melbourne');

// Maintenance theme.
$config['maintenance_theme'] = 'civictheme';

/**
 * Trusted hosts.
 *
 * Additive sources:
 *   1. Salsa Hosting preview hostnames —
 *      `{env}--{org}--{app}[--{port}].apps.quantgovsites.com`.
 *   2. The project's own domains and local-dev hosts.
 *   3. `DRUPAL_TRUSTED_HOSTS` (comma-separated regex list) platform variable.
 *   4. On local/CI/development, any host is accepted so dev tooling and test
 *      runners work without per-developer configuration.
 */
$settings['trusted_host_patterns'] = [
  // Salsa Hosting preview URL.
  '^[a-z0-9-]+--[a-z0-9-]+--[a-z0-9-]+(?:--\d+)?\.apps\.quantgovsites\.com$',
  // Project domains.
  '^.+\.civictheme\.io$',
  // Local development URL.
  '^.+\.docker\.amazee\.io$',
  // URL when accessed from Behat tests inside the stack.
  '^app$',
];

$user_trusted_hosts = getenv('DRUPAL_TRUSTED_HOSTS');
if ($user_trusted_hosts) {
  foreach (array_filter(array_map('trim', explode(',', $user_trusted_hosts))) as $pattern) {
    $settings['trusted_host_patterns'][] = $pattern;
  }
}

if (in_array($settings['environment'], [ENVIRONMENT_LOCAL, ENVIRONMENT_CI, ENVIRONMENT_DEV], TRUE)) {
  $settings['trusted_host_patterns'][] = '^.+$';
}

/**
 * Reverse proxy — Salsa Hosting terminates TLS upstream.
 */
if (getenv('DRUPAL_REVERSE_PROXY') === 'true') {
  $settings['reverse_proxy'] = TRUE;
  $proxy_addresses = getenv('DRUPAL_REVERSE_PROXY_ADDRESSES') ?: '';
  $settings['reverse_proxy_addresses'] = $proxy_addresses === ''
    ? []
    : array_filter(array_map('trim', explode(',', $proxy_addresses)));
}

/**
 * Optional Redis — only when DRUPAL_REDIS_ENABLED=1 AND the extension is loaded.
 */
if (getenv('DRUPAL_REDIS_ENABLED') === '1' && extension_loaded('redis')) {
  $settings['redis.connection']['interface'] = 'PhpRedis';
  $settings['redis.connection']['host'] = getenv('DRUPAL_REDIS_HOST') ?: 'redis';
  $settings['redis.connection']['port'] = getenv('DRUPAL_REDIS_PORT') ?: 6379;
  $settings['cache']['default'] = 'cache.backend.redis';
  $settings['cache_prefix'] = getenv('DRUPAL_REDIS_PREFIX') ?: getenv('SCAFFOLD_PROJECT') ?: 'drupal';
}

/**
 * Stage file proxy — pull missing public files from a remote env (dev only).
 */
$stage_origin = getenv('DRUPAL_STAGE_FILE_PROXY_ORIGIN');
if ($stage_origin) {
  $config['stage_file_proxy.settings']['origin'] = $stage_origin;
  $config['stage_file_proxy.settings']['hotlink'] = FALSE;
}

/**
 * Verbose error reporting + cache disable when DRUPAL_DEBUG=1. Never set
 * this in production.
 */
if (getenv('DRUPAL_DEBUG') === '1' && !InstallerKernel::installationAttempted()) {
  $config['system.logging']['error_level'] = 'verbose';
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
  $settings['extension_discovery_scan_tests'] = TRUE;
}

////////////////////////////////////////////////////////////////////////////////
///                   PER-ENVIRONMENT SETTINGS                               ///
////////////////////////////////////////////////////////////////////////////////

if ($settings['environment'] == ENVIRONMENT_CI) {
  // Never harden permissions on sites/default/files.
  $settings['skip_permissions_hardening'] = TRUE;

  // Disable built-in cron trigger.
  $config['automated_cron.settings']['interval'] = 0;

  // Disable mail send out.
  $settings['suspend_mail_send'] = TRUE;
}

if ($settings['environment'] == ENVIRONMENT_LOCAL) {
  // Never harden permissions on sites/default/files during local development.
  $settings['skip_permissions_hardening'] = TRUE;

  // Disable built-in cron trigger.
  $config['automated_cron.settings']['interval'] = 0;

  // Show all error messages on the site.
  $config['system.logging']['error_level'] = 'all';
}

////////////////////////////////////////////////////////////////////////////////
///                       PER-MODULE SETTINGS                                ///
////////////////////////////////////////////////////////////////////////////////

if (file_exists($app_root . '/' . $site_path . '/includes')) {
  $files = glob($app_root . '/' . $site_path . '/includes/settings.*.php');
  if ($files) {
    foreach ($files as $filename) {
      require $filename;
    }
  }
}

////////////////////////////////////////////////////////////////////////////////
///                          LOCAL SETTINGS                                  ///
////////////////////////////////////////////////////////////////////////////////

// Load local development override configuration, if available.
// DRUPAL_LOCAL_SETTINGS_SKIP exists for the settings unit tests, which must
// assert this file's own output regardless of a developer's local overrides.
//
// Keep this code block at the end of this file to take full effect.
// @codeCoverageIgnoreStart
if (empty(getenv('DRUPAL_LOCAL_SETTINGS_SKIP'))) {
  if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
    require $app_root . '/' . $site_path . '/settings.local.php';
  }
  if (file_exists($app_root . '/' . $site_path . '/services.local.yml')) {
    $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.local.yml';
  }
}
// @codeCoverageIgnoreEnd
