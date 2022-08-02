<?php

/**
 * @file
 * PHP CLI script template.
 *
 * -----------------------------------------------------------------------------
 * PHP script template for single-file CLI scripts without dependency on
 * external packages.
 *
 * To adopt script template:
 * - Replace "PHP CLI script template" with your script human name.
 * - Update print_help() function with your content.
 * - Copy '/tests' directory into your project and update
 *   ExampleScriptUnitTest.php (with a test class inside) to a script file name.
 * - Remove this block of comments.
 * -----------------------------------------------------------------------------
 *
 * Environment variables:
 * - SCRIPT_QUIET: Set to '1' to suppress verbose messages.
 * - SCRIPT_RUN_SKIP: Set to '1' to skip running of the script. Useful when
 *   unit-testing or requiring this file from other files.
 *
 * Usage:
 * @code
 * php add-lint-exclusions /path/to/storybook-static
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

  $target_directory = $argv[1];
  $target_directory = getcwd() . '/' . $target_directory;
  $exclusion = "// phpcs:ignoreFile\n";
  if (file_exists($target_directory) && is_dir($target_directory)) {
    $files = glob($target_directory . '**/*bundle.js');
    if (count($files) === 0) {
      print 'No files found';

      return EXIT_ERROR;
    }
    foreach ($files as $file) {
      $contents = file_get_contents($file);
      file_put_contents($file, $exclusion . $contents);
      print "Added PHPCS lint exclusion to: $file\n";
    }
    return EXIT_SUCCESS;
  }

  print "Directory $target_directory is not readable.\n";

  return EXIT_ERROR;
}

/**
 * Print help.
 */
function print_help() {
  print <<<EOF
Lint exclusion script
------------------------
Arguments:
       Value of the first argument.
Options:
  --help                This help.
Examples:
  php add-lint-exclusions.php path/to/storybook-static-directory
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
