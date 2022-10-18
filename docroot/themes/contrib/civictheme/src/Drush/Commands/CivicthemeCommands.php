<?php

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
 * drush --include=docroot/themes/contrib/civictheme/src/Drush command
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
  public function setBrandColors($light_brand1, $light_brand2, $light_brand3, $dark_brand1, $dark_brand2, $dark_brand3) {
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
  public function clearCache() {
    $this->colorManager = \Drupal::classResolver(CivicthemeColorManager::class);
    $this->colorManager->invalidateCache();
  }

}
