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
 * @endcode
 *
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingBefore
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingAfter
 * phpcs:disable DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter
 */

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
 */
function main(array $argv, $argc) {
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

  $target_directory = $argv[1];
  $target_directory = getcwd() . '/' . $target_directory;

  print "==> Started adding of PHPCS exclusions to files in directory $target_directory." . PHP_EOL;

  if (!file_exists($target_directory) || !is_dir($target_directory)) {
    throw new \Exception(sprintf('Directory "%s" is not readable.', $target_directory) . PHP_EOL);
  }

  $files = glob($target_directory . '**/*.js');

  foreach ($files as $file) {
    if (!file_exists($file)) {
      continue;
    }

    $contents = file_get_contents($file);
    if (str_contains($contents, $template)) {
      print "  > [SKIPPED] $file" . PHP_EOL;
      continue;
    }

    file_put_contents($file, $template . $contents);
    print "  > [ADDED] $file" . PHP_EOL;
  }

  print "==> Finished adding of PHPCS exclusions to files in directory $target_directory." . PHP_EOL;

  return EXIT_SUCCESS;
}

/**
 * Print help.
 */
function print_help() {
  print <<<EOF
Add PHPCS exclusions
--------------------
Arguments:
  Value of the first argument.
Options:
  --help                This help.
Examples:
  php add_phpcs_exclusions.php path/to/storybook-static
EOF;
  print PHP_EOL;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                UTILITIES                                   //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Show a verbose message.
 */
function verbose() {
  if (getenv('SCRIPT_QUIET') != '1') {
    print call_user_func_array('sprintf', func_get_args()) . PHP_EOL;
  }
}

// ////////////////////////////////////////////////////////////////////////// //
//                                ENTRYPOINT                                  //
// ////////////////////////////////////////////////////////////////////////// //

ini_set('display_errors', 1);

if (PHP_SAPI != 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
  die('This script can be only ran from the command line.');
}

// Allow to skip the script run.
if (getenv('SCRIPT_RUN_SKIP') != 1) {
  // Custom error handler to catch errors based on set ERROR_LEVEL.
  set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
      // This error code is not included in error_reporting.
      return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
  });

  try {
    $code = main($argv, $argc);
    if (is_null($code)) {
      throw new \Exception('Script exited without providing an exit code.');
    }
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
