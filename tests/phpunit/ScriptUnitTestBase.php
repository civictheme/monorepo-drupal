<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Class ScriptUnitTestBase.
 *
 * Base class to unit tests scripts.
 *
 * @group scripts
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class ScriptUnitTestBase extends TestCase {

  /**
   * Script to include.
   *
   * @var string
   */
  protected $script;

  /**
   * Temporary directory.
   *
   * @var string
   */
  protected $tmpDir;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    putenv('SCRIPT_RUN_SKIP=1');
    putenv('SCRIPT_QUIET=1');
    $path = getcwd() . DIRECTORY_SEPARATOR . $this->script;
    if (!is_readable($path)) {
      throw new \RuntimeException(sprintf('Unable to include script file %s.', $this->script));
    }
    require_once $path;

    $this->tmpDir = $this->tempdir();

    parent::setUp();
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.ErrorControlOperator)
   */
  protected function tearDown(): void {
    parent::tearDown();
    if (!empty($this->tmpDir) && is_dir($this->tmpDir)) {
      @unlink($this->tmpDir);
    }
  }

  /**
   * Run script with optional arguments.
   *
   * @param array $args
   *   Optional array of arguments to pass to the script.
   * @param bool $verbose
   *   Optional flag to enable verbose output in the script.
   *
   * @return array
   *   Array with the following keys:
   *   - code: (int) Exit code.
   *   - output: (string) Output.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   */
  protected function runScript(array $args = [], $verbose = FALSE): array {
    putenv('SCRIPT_RUN_SKIP=0');
    if ($verbose) {
      putenv('SCRIPT_QUIET=0');
    }
    $command = sprintf('php %s %s', $this->script, implode(' ', $args));
    $output = [];
    $result_code = 1;
    exec($command, $output, $result_code);

    return [
      'code' => $result_code,
      'output' => implode(PHP_EOL, $output),
    ];
  }

  /**
   * Replace path to a fixture file.
   */
  protected function fixtureFile(string $filename): string {
    $path = 'tests/phpunit/fixtures/drupal_configs/' . $filename;
    if (!is_readable($path)) {
      throw new \RuntimeException(sprintf('Unable to find fixture file %s.', $path));
    }

    return $path;
  }

  /**
   * Path to a temporary file.
   */
  protected function toTmpPath(string $filename, string $prefix = NULL): string {
    return $prefix ? $this->tmpDir . DIRECTORY_SEPARATOR . $prefix . DIRECTORY_SEPARATOR . $filename : $this->tmpDir . DIRECTORY_SEPARATOR . $filename;
  }

  /**
   * Print the contents of the temporary directory.
   */
  protected function printTempDir(): void {
    $it = new RecursiveTreeIterator(new RecursiveDirectoryIterator($this->tmpDir, RecursiveDirectoryIterator::SKIP_DOTS | \FilesystemIterator::SKIP_DOTS));
    print PHP_EOL;
    foreach ($it as $value) {
      print $value . PHP_EOL;
    }
  }

  /**
   * Create a random unique temporary directory.
   */
  protected function tempdir(string $dir = NULL, string $prefix = 'tmp_', int $mode = 0700, int $max_attempts = 1000): string {
    if (is_null($dir)) {
      $dir = sys_get_temp_dir();
    }

    $dir = rtrim($dir, DIRECTORY_SEPARATOR);

    if (!is_dir($dir) || !is_writable($dir)) {
      throw new \RuntimeException(sprintf('Temporary directory "%s" does not exist or is not writable.', $dir));
    }

    if (strpbrk($prefix, '\\/:*?"<>|') !== FALSE) {
      throw new \RuntimeException(sprintf('Prefix "%s" contains invalid characters.', $prefix));
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

  /**
   * Recursively replace a value in the array using provided callback.
   */
  protected function arrayReplaceValue(array $array, callable|array $cb): array {
    foreach ($array as $k => $item) {
      if (is_array($item)) {
        $array[$k] = $this->arrayReplaceValue($item, $cb);
      }
      elseif (is_callable($cb)) {
        $array[$k] = $cb($item);
      }
    }

    return $array;
  }

  /**
   * Create temp files from fixtures.
   *
   * @param array $fixture_map
   *   Array of fixture mappings the following structure:
   *   - key: (string) Path to create.
   *   - value: (string) Path to a fixture file to use.
   * @param string $prefix
   *   Optional directory prefix.
   *
   * @return array
   *   Array of created files with the following structure:
   *   - key: (string) Source path (the key from $file_structure).
   *   - value: (string) Path to a fixture file to use.
   */
  protected function createTmpFilesFromFixtures(array $fixture_map, string $prefix = NULL): array {
    $files = [];
    foreach ($fixture_map as $path => $fixture_file) {
      $tmp_path = $this->toTmpPath($path, $prefix);
      $dirname = dirname($tmp_path);

      if (!file_exists($dirname)) {
        mkdir($dirname, 0777, TRUE);
        if (!is_readable($dirname)) {
          throw new \RuntimeException(sprintf('Unable to create temp directory %s.', $dirname));
        }
      }

      // Pass-through preserving/removal values.
      if (is_bool($fixture_file)) {
        $files[$path] = $fixture_file;
        continue;
      }

      // Allow creating empty directories.
      if (empty($fixture_file) || $fixture_file === '.empty') {
        continue;
      }
      $fixture_file = $this->fixtureFile($fixture_file);

      copy($fixture_file, $tmp_path);
      $files[$path] = $tmp_path;
    }

    return $files;
  }

  /**
   * Create temp files from fixtures.
   *
   * @param array $fixture_map
   *   Array of fixture mappings the following structure:
   *   - key: (string) Path to create.
   *   - value: (string) Path to a fixture file to use.
   * @param string $prefix
   *   Optional directory prefix.
   *
   * @return array
   *   Array of created files with the following structure:
   *   - key: (string) Source path (the key from $file_structure).
   *   - value: (string) Path to a fixture file to use.
   *
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  protected function replaceFixturePaths(array $fixture_map, string $prefix = NULL): array {
    foreach ($fixture_map as $k => $v) {
      if (is_array($v)) {
        $fixture_map[$k] = $this->replaceFixturePaths($v, $prefix);
      }
      else {
        $tmp_path = $this->toTmpPath($v, $prefix);

        $dirname = dirname($tmp_path);
        if (!file_exists($dirname)) {
          mkdir($dirname, 0777, TRUE);
          if (!is_readable($dirname)) {
            throw new \RuntimeException(sprintf('Unable to create temp directory %s.', $dirname));
          }
        }

        // Pass-through preserving/removal values.
        if (is_bool($v)) {
          $fixture_map[$k] = $v;
          continue;
        }

        // Allow creating empty directories.
        if (empty($v) || $v === '.empty') {
          continue;
        }

        $fixture_file = $this->fixtureFile($v);
        copy($fixture_file, $tmp_path);

        $fixture_map[$k] = $tmp_path;
      }
    }

    return $fixture_map;
  }

  /**
   * Recursively copy files and directories.
   *
   * The contents of $src will be copied as the contents of $dst.
   *
   * @param string $src
   *   Source directory to copy from.
   * @param string $dst
   *   Destination directory to copy to.
   * @param array $exclude
   *   Optional array of entries to exclude.
   * @param int $permissions
   *   Permissions to set on created directories. Defaults to 0755.
   * @param bool $copy_empty_dirs
   *   Flag to copy empty directories. Defaults to FALSE.
   *
   * @return bool
   *   TRUE if the result of copy was successful, FALSE otherwise.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public function fileCopyRecursively(string $src, string $dst, array $exclude = [], $permissions = 0755, $copy_empty_dirs = FALSE): bool {
    $parent = dirname($dst);

    if (!is_dir($parent)) {
      mkdir($parent, $permissions, TRUE);
    }

    // Note that symlink target must exist.
    if (is_link($src)) {
      // Changing dir symlink will be relevant to the current destination's file
      // directory.
      $cur_dir = getcwd();
      if (!$cur_dir) {
        throw new \RuntimeException('Unable to get current working directory.');
      }

      chdir($parent);
      $ret = TRUE;
      if (!is_readable(basename($dst)) && !empty(readlink($src))) {
        $ret = symlink(readlink($src), basename($dst));
      }
      chdir($cur_dir);

      return $ret;
    }

    if (is_file($src)) {
      $ret = copy($src, $dst);
      if ($ret) {
        $perms = fileperms($src);
        if ($perms === FALSE) {
          throw new \RuntimeException(sprintf('Unable to get permissions for %s.', $src));
        }
        chmod($dst, $perms);
      }

      return $ret;
    }

    if (!is_dir($dst) && $copy_empty_dirs) {
      mkdir($dst, $permissions, TRUE);
    }

    $dir = dir($src);
    while ($dir && FALSE !== $entry = $dir->read()) {
      if ($entry == '.' || $entry == '..' || in_array($entry, $exclude)) {
        continue;
      }
      $this->fileCopyRecursively($src . DIRECTORY_SEPARATOR . $entry, $dst . DIRECTORY_SEPARATOR . $entry, $exclude, $permissions, $copy_empty_dirs);
    }

    if ($dir) {
      $dir->close();
    }

    return TRUE;
  }

}
