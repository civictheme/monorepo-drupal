<?php

/**
 * @file
 * Add PHPCS exclusion comments to generated static storybook assets.
 *
 * This is required for environments where PHPCS configurations cannot be
 * modified. In such environments, generated storybook assets would report as
 * false positive PHPCS violations.
 *
 * Environment variables:
 * - SCRIPT_QUIET: Set to '1' to suppress verbose messages.
 * - SCRIPT_RUN_SKIP: Set to '1' to skip running of the script. Useful when
 *   unit-testing or requiring this file from other files.
 *
 * Usage:
 * @code
 * php add_phpcs_exclusions.php path/to/storybook-static
 * php add_phpcs_exclusions.php path/to/storybook-static,path/to/dist
 * @endcode
 *
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingBefore
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingAfter
 * phpcs:disable DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter
 */

declare(strict_types=1);

/**
 * Defines exit codes.
 */
define('EXIT_SUCCESS', 0);
define('EXIT_ERROR', 1);

/**
 * Defines error level to be reported as an error.
 */
define('ERROR_LEVEL', E_USER_WARNING);

/**
 * Main functionality.
 *
 * @SuppressWarnings(PHPMD.MissingImport)
 */
function main(array $argv, int $argc): int {
  if (in_array($argv[1] ?? NULL, ['--help', '-help', '-h', '-?'])) {
    print_help();

    return EXIT_SUCCESS;
  }

  // Show help if not enough or more than required arguments.
  if ($argc < 2 || $argc > 2) {
    print_help();

    return EXIT_ERROR;
  }

  $template = "// phpcs:ignoreFile\n";

  $extensions = ['js'];

  $target_directories = $argv[1];
  $target_directories_original = $target_directories;

  print sprintf('==> Started adding of PHPCS exclusions to files in directories %s.', $target_directories_original) . PHP_EOL;

  $target_directories = explode(',', (string) $target_directories);

  $files = [];
  foreach ($target_directories as $target_directory) {
    $target_directory = getcwd() . '/' . $target_directory;

    if (file_exists($target_directory) && is_dir($target_directory)) {
      $directory = new RecursiveDirectoryIterator($target_directory);
      $iterator = new RecursiveIteratorIterator($directory);
      /** @var \SplFileInfo $file */
      foreach ($iterator as $file) {
        if (in_array($file->getExtension(), $extensions)) {
          $files[] = $file->getPathname();
        }
      }
    }
  }

  if (empty($files)) {
    throw new \Exception(sprintf('No files found in directories "%s".', $target_directories_original) . PHP_EOL);
  }

  foreach ($files as $file) {
    if (!file_exists($file)) {
      continue;
    }

    $contents = file_get_contents($file) ?: '';
    if (str_contains($contents, $template)) {
      print '  > [SKIPPED] ' . $file . PHP_EOL;
      continue;
    }

    file_put_contents($file, $template . $contents);
    print '  > [ADDED] ' . $file . PHP_EOL;
  }

  print sprintf('==> Finished adding of PHPCS exclusions to files in directory %s.', $target_directory) . PHP_EOL;

  return EXIT_SUCCESS;
}

/**
 * Print help.
 */
function print_help(): void {
  print <<<EOF
Add PHPCS exclusions
--------------------
Arguments:
  Value of the first argument.
Options:
  --help                This help.
Examples:
  php add_phpcs_exclusions.php path/to/storybook-static,path/to/dist
EOF;
  print PHP_EOL;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                UTILITIES                                   //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Show a verbose message.
 */
function verbose(): void {
  if (getenv('SCRIPT_QUIET') != '1') {
    print sprintf(...func_get_args()) . PHP_EOL;
  }
}

// ////////////////////////////////////////////////////////////////////////// //
//                                ENTRYPOINT                                  //
// ////////////////////////////////////////////////////////////////////////// //

ini_set('display_errors', 1);

if (PHP_SAPI !== 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
  die('This script can be only ran from the command line.');
}

// Allow to skip the script run.
if (getenv('SCRIPT_RUN_SKIP') != 1) {
  // Custom error handler to catch errors based on set ERROR_LEVEL.
  // @phpstan-ignore-next-line
  set_error_handler(static function ($severity, $message, $file, $line): void {
    if ((error_reporting() & $severity) === 0) {
      // This error code is not included in error_reporting.
      return;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
  });

  try {
    $code = main($argv, $argc);
    exit($code);
  }
  catch (\ErrorException $exception) {
    if ($exception->getSeverity() <= ERROR_LEVEL) {
      print PHP_EOL . 'RUNTIME ERROR: ' . $exception->getMessage() . PHP_EOL;
      exit($exception->getCode() == 0 ? EXIT_ERROR : $exception->getCode());
    }
  }
  catch (\Exception $exception) {
    print PHP_EOL . 'ERROR: ' . $exception->getMessage() . PHP_EOL;
    exit($exception->getCode() == 0 ? EXIT_ERROR : $exception->getCode());
  }
}
