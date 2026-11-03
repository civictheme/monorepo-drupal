<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter;

use Drupal\Core\Theme\StarterKitInterface;

use function Symfony\Component\String\u;

/**
 * Post-processes themes generated from this starterkit.
 *
 * Core's generate-theme edits and renames only visible files: its edit and
 * rename finders skip dot files, so .storybook/*, .ahoy.yml and friends would
 * keep the kit identity. Its ignore globs also cannot match dot-leading
 * entries nested under ignored directories (components/x/.gitkeep), so build
 * output can partially leak into the generated theme. This post-processor
 * applies the same rename patterns core uses to every remaining file,
 * including dot files, and removes build-output leftovers.
 *
 * This file itself never ships into generated themes: it is part of core's
 * default starterkit ignore list, restated in the kit's starterkit.yml.
 */
final class StarterKit implements StarterKitInterface {

  /**
   * The machine name of this starterkit.
   */
  const MACHINE_NAME = 'civictheme_starter';

  /**
   * The label of this starterkit.
   */
  const LABEL = 'CivicTheme Starter';

  /**
   * {@inheritdoc}
   */
  public static function postProcess(string $working_dir, string $machine_name, string $theme_name): void {
    // Build-output and kit-only trees whose dot-leading entries escape the
    // starterkit ignore globs.
    foreach (['build', 'components', 'dist', 'node_modules', 'storybook-static', '.logs'] as $directory) {
      self::removeDirectory($working_dir . '/' . $directory);
    }

    $old = array_values(self::namePatterns(self::MACHINE_NAME, self::LABEL));
    $new = array_values(self::namePatterns($machine_name, $theme_name));

    // Two-pass replacement through unique placeholders, mirroring core's
    // GenerateTheme, so a replacement can never introduce text that another
    // pattern then matches.
    $placeholders = array_map(static fn (int $index): string => "__STARTERKIT_PATTERN_{$index}__", array_keys($old));

    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($working_dir, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_FILEINFO)
    );
    /** @var \SplFileInfo $file */
    foreach ($iterator as $file) {
      if (!$file->isFile()) {
        continue;
      }

      $path = $file->getPathname();
      $contents = (string) file_get_contents($path);
      // Leave binary files (images) untouched.
      if (!str_contains($contents, "\0")) {
        $replaced = str_replace($placeholders, $new, str_replace($old, $placeholders, $contents));
        // Core writes the generated info.yml after its own rename pass: its
        // generator key intentionally records the source kit and must keep
        // the kit identity, so restore it from the pre-replacement contents.
        if ($file->getFilename() === $machine_name . '.info.yml'
          && preg_match('/^generator:.*$/m', $contents, $generator) === 1) {
          $replaced = (string) preg_replace('/^generator:.*$/m', $generator[0], $replaced);
        }
        if ($replaced !== $contents) {
          file_put_contents($path, $replaced);
        }
      }

      $filename = $file->getFilename();
      $renamed = str_replace($placeholders, $new, str_replace($old, $placeholders, $filename));
      if ($renamed !== $filename) {
        rename($path, $file->getPath() . '/' . $renamed);
      }
    }
  }

  /**
   * Returns the rename patterns for a machine name and label.
   *
   * Mirrors the patterns (and their order) of core's
   * \Drupal\Core\Command\GenerateTheme::namePatterns().
   *
   * @param string $machine_name
   *   The theme machine name.
   * @param string $label
   *   The theme label.
   *
   * @return array<string, string>
   *   The patterns keyed by pattern name.
   */
  protected static function namePatterns(string $machine_name, string $label): array {
    return [
      'machine_name' => $machine_name,
      'machine_name_camel' => (string) u($machine_name)->camel(),
      'machine_name_pascal' => (string) u($machine_name)->camel()->title(),
      'machine_name_title' => (string) u($machine_name)->title(),
      'label' => $label,
      'label_camel' => (string) u($label)->camel(),
      'label_pascal' => (string) u($label)->camel()->title(),
      'label_title' => (string) u($label)->title(),
    ];
  }

  /**
   * Removes a directory recursively, if it exists.
   *
   * @param string $directory
   *   The directory path.
   */
  protected static function removeDirectory(string $directory): void {
    if (!is_dir($directory)) {
      return;
    }

    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_FILEINFO),
      \RecursiveIteratorIterator::CHILD_FIRST
    );
    /** @var \SplFileInfo $file */
    foreach ($iterator as $file) {
      if ($file->isDir() && !$file->isLink()) {
        rmdir($file->getPathname());
        continue;
      }
      unlink($file->getPathname());
    }
    rmdir($directory);
  }

}
