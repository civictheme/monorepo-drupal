<?php

declare(strict_types=1);

namespace Drush\Commands;

use Drupal\civictheme\CivicthemeColorRecipeManager;

/**
 * CivicTheme Drush commands file.
 *
 * Has to be declared in the Drush\Commands namespace as there is no autoload
 * for Drush commands called directly.
 *
 * Bootstrap level is controlled per-command.
 *
 * @see https://www.drush.org/latest/bootstrap
 *
 * Usage:
 * drush --include=web/themes/contrib/civictheme/src/Drush command
 *
 * phpcs:disable Drupal.Commenting.DocComment.ParamGroup
 * phpcs:disable Drupal.Commenting.FunctionComment.MissingParamType
 * phpcs:disable Drupal.Commenting.DocComment.ParamNotFirst
 */
class CivicthemeCommands extends DrushCommands {

  /**
   * The colour recipe manager.
   *
   * @var \Drupal\civictheme\CivicthemeColorRecipeManager
   */
  protected $colorRecipeManager;

  /**
   * Imports a colour recipe and its emitted overrides CSS.
   *
   * Both artefacts are produced by the CivicTheme UI Kit colour picker:
   * either exported from the picker UI embedded in theme settings, or
   * emitted on the command line with
   * `npx @civictheme/colour-picker emit --recipe recipe.json --out out/`
   * (use `out/overrides.scss` - it contains plain CSS - as the CSS file).
   *
   * @param string $recipe_file
   *   Path to the recipe JSON file.
   * @param string $css_file
   *   Path to the emitted overrides CSS file.
   *
   * @command civictheme:import-colors
   * @usage drush civictheme:import-colors recipe.json overrides.css
   *
   * @bootstrap full
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function importColors(string $recipe_file, string $css_file): void {
    // @phpstan-ignore-next-line
    $this->colorRecipeManager = \Drupal::classResolver(CivicthemeColorRecipeManager::class);

    try {
      $recipe = $this->readFile($recipe_file);
      $css = $this->readFile($css_file);

      $decoded = json_decode($recipe, TRUE);
      if (!is_array($decoded) || !array_key_exists('version', $decoded)) {
        throw new \Exception(sprintf('File "%s" is not a valid colour recipe (expecting a JSON object with a "version" key).', $recipe_file));
      }

      $this->colorRecipeManager->save($recipe, $css);
      $this->logger()->success('Successfully imported the colour recipe and overrides CSS.');
    }
    catch (\Exception $exception) {
      $this->logger()->error($exception->getMessage());
    }
  }

  /**
   * Generate the colour overrides stylesheet for the current theme.
   *
   * @command civictheme:stylesheet
   *
   * @param $suffix
   *   Optional stylesheet suffix. Defaults to the currently active theme.
   *
   * @usage drush civictheme:stylesheet
   *   Generate stylesheet with the name of the active theme as a suffix.
   * @usage drush civictheme:stylesheet mysuffix
   *   Generate stylesheet with "mysuffix" suffix.
   *
   * @bootstrap full
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function stylesheet(?string $suffix = NULL): void {
    // @phpstan-ignore-next-line
    $this->colorRecipeManager = \Drupal::classResolver(CivicthemeColorRecipeManager::class);

    if (empty($suffix)) {
      // @phpstan-ignore-next-line
      $suffix = \Drupal::theme()->getActiveTheme()->getName();
    }

    $stylesheet_path = $this->colorRecipeManager->stylesheet($suffix);

    if (!$stylesheet_path) {
      $this->logger()->error(sprintf('Unable to generate stylesheet with suffix "%s" - no overrides CSS is stored. Import a recipe first with civictheme:import-colors or save one in theme settings.', $suffix));

      return;
    }

    $this->logger()->success(sprintf('Successfully generated stylesheet with suffix "%s" at path %s.', $suffix, $stylesheet_path));
  }

  /**
   * Clears CivicTheme and Drupal cache.
   *
   * All assets created dynamically will be purged.
   *
   * @command civictheme:clear-cache
   * @usage drush civictheme:clear-cache
   *
   * @bootstrap full
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function clearCache(): void {
    // @phpstan-ignore-next-line
    $this->colorRecipeManager = \Drupal::classResolver(CivicthemeColorRecipeManager::class);
    $this->colorRecipeManager->invalidateCache();
  }

  /**
   * Read a file and return its contents.
   */
  protected function readFile(string $filepath): string {
    if (!is_file($filepath) || !is_readable($filepath)) {
      throw new \Exception(sprintf('File "%s" does not exist or is not readable.', $filepath));
    }

    $contents = file_get_contents($filepath);
    if ($contents === FALSE) {
      throw new \Exception(sprintf('Unable to read file "%s".', $filepath));
    }

    return $contents;
  }

}
