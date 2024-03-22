<?php

declare(strict_types=1);

namespace Drush\Commands;

use Drupal\civictheme\CivicthemeColorManager;
use Drupal\civictheme\CivicthemeConstants;

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
   * The color manager.
   *
   * @var \Drupal\civictheme\CivicthemeColorManager
   */
  protected $colorManager;

  /**
   * Sets CivicTheme Brand colors which will update Palette colors.
   *
   * @param string $light_brand1
   *   Light Brand 1 color.
   * @param string $light_brand2
   *   Light Brand 2 color.
   * @param string $light_brand3
   *   Light Brand 3 color.
   * @param string $dark_brand1
   *   Dark Brand 1 color.
   * @param string $dark_brand2
   *   Dark Brand 2 color.
   * @param string $dark_brand3
   *   Dark Brand 3 color.
   *
   * @command civictheme:set-brand-colors
   * @usage drush civictheme:set-brand-colors #ff0000 #00ff00 #0000ff #00ffff #ffff00 #ff00ff
   *
   * @bootstrap full
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function setBrandColors($light_brand1, $light_brand2, $light_brand3, $dark_brand1, $dark_brand2, $dark_brand3): void {
    $this->colorManager = \Drupal::classResolver(CivicthemeColorManager::class);

    try {
      $this->colorManager->setColors([
        CivicthemeColorManager::COLOR_TYPE_BRAND => [
          CivicthemeConstants::THEME_LIGHT => [
            'brand1' => $light_brand1,
            'brand2' => $light_brand2,
            'brand3' => $light_brand3,
          ],
          CivicthemeConstants::THEME_DARK => [
            'brand1' => $dark_brand1,
            'brand2' => $dark_brand2,
            'brand3' => $dark_brand3,
          ],
        ],
        CivicthemeColorManager::COLOR_TYPE_PALETTE => [],
      ])
        ->save(TRUE);
    }
    catch (\Exception $exception) {
      $this->logger()->error($exception->getMessage());
    }
  }

  /**
   * Generate stylesheet for the current theme.
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
  public function stylesheet(string $suffix = NULL): void {
    $this->colorManager = \Drupal::classResolver(CivicthemeColorManager::class);

    if (empty($suffix)) {
      $suffix = \Drupal::theme()->getActiveTheme()->getName();
    }

    $stylesheet_path = $this->colorManager->stylesheet($suffix);

    if (!$stylesheet_path) {
      $this->logger()->error(sprintf('Unable to generate stylesheet with suffix "%s".', $suffix));

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
    $this->colorManager = \Drupal::classResolver(CivicthemeColorManager::class);
    $this->colorManager->invalidateCache();
  }

}
