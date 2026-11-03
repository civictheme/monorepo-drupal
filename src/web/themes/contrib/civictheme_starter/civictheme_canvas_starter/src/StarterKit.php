<?php

declare(strict_types=1);

namespace Drupal\civictheme_canvas_starter;

use Drupal\Core\Theme\StarterKitInterface;

use function Symfony\Component\String\u;

/**
 * Post-processes overlay themes generated from this canvas starterkit.
 *
 * Core's generate-theme renames this kit's own identity, but it cannot know
 * that the overlay belongs to a theme generated from the main kit: the
 * `base theme:` key and the component `replaces:` targets still name
 * `civictheme_starter`. The generated overlay's machine name must end in
 * `_canvas`; the main theme's machine name is everything before that suffix,
 * and every main-kit reference is rewritten to it.
 *
 * Core's rename pass also skips dot files, so this kit's own identity
 * patterns are re-applied to every file the same way the main kit's
 * post-processor does.
 *
 * This file never ships into generated overlays: it is part of core's
 * default starterkit ignore list, restated in the kit's starterkit.yml.
 *
 * @see \Drupal\civictheme_starter\StarterKit
 */
final class StarterKit implements StarterKitInterface {

  /**
   * The machine name of this starterkit.
   */
  const MACHINE_NAME = 'civictheme_canvas_starter';

  /**
   * The label of this starterkit.
   */
  const LABEL = 'CivicTheme Canvas Starter';

  /**
   * The machine name of the main starterkit this overlay belongs to.
   */
  const MAIN_MACHINE_NAME = 'civictheme_starter';

  /**
   * The label of the main starterkit.
   */
  const MAIN_LABEL = 'CivicTheme Starter';

  /**
   * The required machine name suffix of generated overlays.
   */
  const SUFFIX = '_canvas';

  /**
   * {@inheritdoc}
   */
  public static function postProcess(string $working_dir, string $machine_name, string $theme_name): void {
    if (!str_ends_with($machine_name, self::SUFFIX)) {
      throw new \InvalidArgumentException(sprintf(
        "The canvas overlay machine name must end in '%s' (the main theme's machine name plus the suffix); '%s' given.",
        self::SUFFIX,
        $machine_name
      ));
    }

    $main_machine_name = substr($machine_name, 0, -strlen(self::SUFFIX));
    // The main theme's label is only needed to map label-derived patterns;
    // kit files reference the main kit by machine name, so this is a
    // best-effort derivation for prose.
    $main_label = str_ends_with($theme_name, ' Canvas')
      ? substr($theme_name, 0, -strlen(' Canvas'))
      : ucwords(str_replace('_', ' ', $main_machine_name));

    // First re-apply this kit's own identity to the files core's rename pass
    // skips (dot files), then rewrite every main-kit reference to the main
    // theme generated from the main kit.
    self::replacePatterns(
      $working_dir,
      $machine_name,
      self::namePatterns(self::MACHINE_NAME, self::LABEL),
      self::namePatterns($machine_name, $theme_name)
    );
    self::replacePatterns(
      $working_dir,
      $machine_name,
      self::namePatterns(self::MAIN_MACHINE_NAME, self::MAIN_LABEL),
      self::namePatterns($main_machine_name, $main_label)
    );
  }

  /**
   * Replaces identity patterns in every file's contents and name.
   *
   * @param string $working_dir
   *   The generated theme directory.
   * @param string $machine_name
   *   The generated theme machine name, used to locate its info.yml, whose
   *   `generator:` line intentionally keeps the kit identity.
   * @param array<string, string> $old_patterns
   *   Patterns to replace, from namePatterns().
   * @param array<string, string> $new_patterns
   *   Replacements, from namePatterns().
   */
  protected static function replacePatterns(string $working_dir, string $machine_name, array $old_patterns, array $new_patterns): void {
    $old = array_values($old_patterns);
    $new = array_values($new_patterns);

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
      // Leave binary files untouched.
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

}
