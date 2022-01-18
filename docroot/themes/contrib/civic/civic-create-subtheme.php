<?php

/**
 * @file
 * Civic sub-theme scaffolding.
 *
 * Usage:
 * @code
 * php civic-create-subtheme.php new_machine_name "Human name" "Human description"
 * @endcode
 *
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingBefore
 * phpcs:disable Drupal.Commenting.InlineComment.SpacingAfter
 * phpcs:disable DrupalPractice.Commenting.CommentEmptyLine.SpacingAfter
 */

/**
 * Defines installer exist codes.
 */
define('SCAFFOLD_EXIT_SUCCESS', 0);
define('SCAFFOLD_EXIT_ERROR', 1);

/**
 * Main install functionality.
 */
function main(array $argv) {
  // Show help if not enough arguments or help was explicitly called.
  if (count($argv) != 4 || in_array($argv[1], ['--help', '-help', '-h', '-?'])) {
    print_help();
  }

  // Collect and validate values from arguments.
  $new_theme_machine_name = trim($argv[1]);
  $new_theme_name = trim($argv[2]);
  $new_theme_description = trim($argv[3]);
  validate_theme_machine_name($new_theme_machine_name);

  // Find Drupal docroot.
  $docroot = drupal_finder_find_root(getcwd());

  // Prepare theme stub.
  $stub_path = prepare_stub($docroot);

  // Process stub.
  process_stub($stub_path, [
    'machine_name' => $new_theme_machine_name,
    'name' => $new_theme_name,
    'description' => $new_theme_description,
  ]);

  // Copy files from stub to the new theme path.
  $new_theme_path = $docroot . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . $new_theme_machine_name;
  file_copy_recursively($stub_path, $new_theme_path);

  // Remove stub directory.
  @unlink($stub_path);

  // Print footer message.
  print_footer($new_theme_name, $new_theme_machine_name, $new_theme_path);

  return SCAFFOLD_EXIT_SUCCESS;
}

/**
 * Print help.
 */
function print_help() {
  print <<<EOF
Civic Starter Kit scaffolding
-----------------------------

Arguments:
  machine_name         Theme machine name.
  name                 Theme human-readable name.
  Description          Theme description.

Options:
  --help               This help.

Examples:
  php civic-create-subtheme.php civic_demo "Civic Demo" "Demo sub-theme for a Civic theme."

EOF;
  print PHP_EOL;
}

/**
 * Print footer.
 */
function print_footer($name, $machine_name, $path) {
  print <<<EOF

  $name ($machine_name) sub-theme was created successfully in "$path".

  Next steps:
    cd $path
    npm install
    npm run build
    drush theme:enable $machine_name -y
    drush config-set system.theme default $machine_name

EOF;
  print PHP_EOL;
}

/**
 * Validate theme machine name.
 */
function validate_theme_machine_name($name) {
  if (!preg_match('/^[a-z][a-z_0-9]*$/', $name)) {
    throw new \RuntimeException('Invalid machine name. Theme machine name can only start with lowercase letters and contain lowercase letters, numbers and underscores.');
  }
}

/**
 * Prepare theme stub files.
 *
 * @param string $drupal_root
 *   Drupal root directory.
 *
 * @return string
 *   Path to stub directory.
 */
function prepare_stub($drupal_root) {
  $tmp_dir = file_tempdir();
  $starter_kit_dir = find_starter_kit_dir($drupal_root);
  file_copy_recursively($starter_kit_dir, $tmp_dir);

  return $tmp_dir;
}

/**
 * Process stub directory.
 */
function process_stub($dir, $options) {
  $machine_name_hyphenated = str_replace('_', '-', $options['machine_name']);
  // @formatter:off
  // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing.TooMuchSpaceAfterComma
  // phpcs:disable Drupal.WhiteSpace.Comma.TooManySpaces
  file_replace_dir_content('civic_starter_kit',              $options['machine_name'], $dir);
  file_replace_dir_content('civic-starter-kit',              $machine_name_hyphenated, $dir);
  file_replace_dir_content('Civic Starter Kit description.', $options['description'],  $dir);
  file_replace_dir_content('Civic Starter Kit',              $options['name'],         $dir);

  file_replace_string_filename('civic_starter_kit',          $options['machine_name'], $dir);
  // @formatter:on
  // phpcs:enable Generic.Functions.FunctionCallArgumentSpacing.TooMuchSpaceAfterComma
  // phpcs:enable Drupal.WhiteSpace.Comma.TooManySpaces
}

/**
 * Find starter kit.
 */
function find_starter_kit_dir($drupal_root) {
  $candidates = [
    $drupal_root . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
    $drupal_root . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'all' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
    $drupal_root . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'all' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
    $drupal_root . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'contrib' . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
    $drupal_root . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'all' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'contrib' . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
    $drupal_root . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'all' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'civic' . DIRECTORY_SEPARATOR . 'civic_starter_kit',
  ];

  foreach ($candidates as $candidate) {
    if (file_exists($candidate)) {
      return $candidate;
    }
  }

  throw new \Exception('Unable to find Civic starter kit location.');
}

// ////////////////////////////////////////////////////////////////////////// //
//                        DRUPAL FINDER                                       //
// ////////////////////////////////////////////////////////////////////////// //
//                                                                            //
// Taken from webflo/drupal-finder                                            //
// https://github.com/webflo/drupal-finder/blob/master/src/DrupalFinder.php   //
//                                                                            //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Discover Drupal document root.
 *
 * @param string $start_path
 *   The path to start the search from.
 *
 * @throws \Exception
 *   If not able to find Drupal root.
 */
function drupal_finder_find_root($start_path) {
  foreach ([TRUE, FALSE] as $follow_symlinks) {
    $path = $start_path;
    if ($follow_symlinks && is_link($path)) {
      $path = realpath($path);
    }

    // Check the start path.
    if (drupal_finder_is_root($path)) {
      return $path;
    }
    else {
      // Move up dir by dir and check each.
      while ($path = drupal_finder_shift_path_up($path)) {
        if ($follow_symlinks && is_link($path)) {
          $path = realpath($path);
        }
        if (drupal_finder_is_root($path)) {
          return $path;
        }
      }
    }
  }

  throw new \Exception('Unable to find Drupal root.');
}

/**
 * Determine if provided path is a valid Drupal root.
 */
function drupal_finder_is_root($path) {
  if (
    !empty($path) &&
    is_dir($path) && file_exists($path . DIRECTORY_SEPARATOR . 'autoload.php') &&
    file_exists($path . DIRECTORY_SEPARATOR . trim(getenv('COMPOSER')) ?: 'composer.json')
  ) {
    // Additional check for the presence of core/composer.json to
    // grant it is not a Drupal 7 site with a base folder named "core".
    $candidate = 'core' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'common.inc';
    if (
      file_exists($path . DIRECTORY_SEPARATOR . $candidate) &&
      file_exists($path . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core.services.yml')
    ) {
      if (
        file_exists($path . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'misc' . DIRECTORY_SEPARATOR . 'drupal.js') ||
        file_exists($path . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'drupal.js')
      ) {
        return TRUE;
      }
    }
  }

  return FALSE;
}

/**
 * Returns parent directory.
 *
 * @param string $path
 *   Path to start the traversal from.
 *
 * @return string|false
 *   Parent path of given path or false when $path is filesystem root
 */
function drupal_finder_shift_path_up($path) {
  $parent = dirname($path);

  return in_array($parent, ['.', $path]) ? FALSE : $parent;
}

// ////////////////////////////////////////////////////////////////////////// //
//                        FILE MANIPULATORS                                   //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Recursively copy files.
 */
function file_copy_recursively($src, $dst, $permissions = 0755, $copy_empty_dirs = FALSE) {
  $parent = dirname($dst);

  if (!is_dir($parent)) {
    mkdir($parent, $permissions, TRUE);
  }

  // Note that symlink target must exist.
  if (is_link($src)) {
    // Changing dir symlink will be relevant to the current destination's file
    // directory.
    $cur_dir = getcwd();
    chdir($parent);
    $ret = TRUE;
    if (!is_readable(basename($dst))) {
      $ret = symlink(readlink($src), basename($dst));
    }
    chdir($cur_dir);
    return $ret;
  }

  if (is_file($src)) {
    $ret = copy($src, $dst);
    if ($ret) {
      chmod($dst, fileperms($src));
    }
    return $ret;
  }

  if (!is_dir($dst) && $copy_empty_dirs) {
    mkdir($dst, $permissions, TRUE);
  }

  $dir = dir($src);
  while (FALSE !== $entry = $dir->read()) {
    if ($entry == '.' || $entry == '..') {
      continue;
    }
    file_copy_recursively("$src/$entry", "$dst/$entry", $permissions);
  }

  $dir->close();
  return TRUE;
}

/**
 * Replace file content.
 */
function file_replace_file_content($needle, $replacement, $filename) {
  if (!is_readable($filename) || file_is_excluded_from_processing($filename)) {
    return FALSE;
  }

  $content = file_get_contents($filename);

  if (is_regex($needle)) {
    $replaced = preg_replace($needle, $replacement, $content);
  }
  else {
    $replaced = str_replace($needle, $replacement, $content);
  }
  if ($replaced != $content) {
    file_put_contents($filename, $replaced);
  }
}

/**
 * Replace directory content.
 */
function file_replace_dir_content($needle, $replacement, $dir) {
  $files = file_scandir_recursive($dir, file_ignore_paths());
  foreach ($files as $filename) {
    file_replace_file_content($needle, $replacement, $filename);
  }
}

/**
 * Replace a string in the file name.
 */
function file_replace_string_filename($search, $replace, $dir) {
  $files = file_scandir_recursive($dir, file_ignore_paths());
  foreach ($files as $filename) {
    $new_filename = str_replace($search, $replace, $filename);
    if ($filename != $new_filename) {
      $new_dir = dirname($new_filename);
      if (!is_dir($new_dir)) {
        mkdir($new_dir, 0777, TRUE);
      }
      rename($filename, $new_filename);
    }
  }
}

/**
 * Recursively scan directory for files.
 */
function file_scandir_recursive($dir, $ignore_paths = [], $include_dirs = FALSE) {
  $discovered = [];

  if (is_dir($dir)) {
    $paths = array_diff(scandir($dir), ['.', '..']);
    foreach ($paths as $path) {
      $path = $dir . '/' . $path;
      foreach ($ignore_paths as $ignore_path) {
        // Exlude based on sub-path match.
        if (strpos($path, $ignore_path) !== FALSE) {
          continue(2);
        }
      }
      if (is_dir($path)) {
        if ($include_dirs) {
          $discovered[] = $path;
        }
        $discovered = array_merge($discovered, file_scandir_recursive($path, $ignore_paths, $include_dirs));
      }
      else {
        $discovered[] = $path;
      }
    }
  }

  return $discovered;
}

/**
 * Ignore path.
 */
function file_ignore_paths() {
  return array_merge([
    '/.git/',
    '/.idea/',
    '/vendor/',
    '/node_modules/',
    '/.data/',
  ], file_internal_paths());
}

/**
 * Internal paths to ignore.
 */
function file_internal_paths() {
  return [];
}

/**
 * Check if the file ia excluded from the processing.
 */
function file_is_excluded_from_processing($filename) {
  $excluded_patterns = [
    '.+\.png',
    '.+\.jpg',
    '.+\.jpeg',
    '.+\.bpm',
    '.+\.tiff',
  ];

  return preg_match('/^(' . implode('|', $excluded_patterns) . ')$/', $filename);
}

/**
 * Creates a random unique temporary directory.
 */
function file_tempdir($dir = NULL, $prefix = 'tmp_', $mode = 0700, $max_attempts = 1000) {
  if (is_null($dir)) {
    $dir = sys_get_temp_dir();
  }

  $dir = rtrim($dir, DIRECTORY_SEPARATOR);

  if (!is_dir($dir) || !is_writable($dir)) {
    return FALSE;
  }

  if (strpbrk($prefix, '\\/:*?"<>|') !== FALSE) {
    return FALSE;
  }
  $attempts = 0;

  do {
    $path = sprintf('%s%s%s%s', $dir, DIRECTORY_SEPARATOR, $prefix, mt_rand(100000, mt_getrandmax()));
  } while (!mkdir($path, $mode) && $attempts++ < $max_attempts);

  if (!is_dir($path) || !is_writable($path)) {
    throw new \RuntimeException(sprintf('Unable to create temporary directory "%s".', $path));
  }

  return $path;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                HELPERS                                     //
// ////////////////////////////////////////////////////////////////////////// //

/**
 * Check if the provided string is a regular expression.
 */
function is_regex($str) {
  if (preg_match('/^(.{3,}?)[imsxuADU]*$/', $str, $m)) {
    $start = substr($m[1], 0, 1);
    $end = substr($m[1], -1);

    if ($start === $end) {
      return !preg_match('/[*?[:alnum:] \\\\]/', $start);
    }

    foreach ([['{', '}'], ['(', ')'], ['[', ']'], ['<', '>']] as $delimiters) {
      if ($start === $delimiters[0] && $end === $delimiters[1]) {
        return TRUE;
      }
    }
  }

  return FALSE;
}

// ////////////////////////////////////////////////////////////////////////// //
//                                ENTRYPOINT                                  //
// ////////////////////////////////////////////////////////////////////////// //

ini_set('display_errors', 1);

if (PHP_SAPI != 'cli' || !empty($_SERVER['REMOTE_ADDR'])) {
  die('This script can be only ran from the command line.');
}

try {
  $code = main($argv, $argc);
  if (is_null($code)) {
    throw new \Exception('Script exited without providing an exit code.');
  }
  exit($code);
}
catch (\Exception $exception) {
  print PHP_EOL . 'ERROR: ' . $exception->getMessage() . PHP_EOL;
  exit($exception->getCode() == 0 ? SCAFFOLD_EXIT_ERROR : $exception->getCode());
}
