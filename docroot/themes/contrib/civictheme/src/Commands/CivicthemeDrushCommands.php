<?php

namespace Drush\Commands;

use Drupal\civictheme\CivicthemeColorManager;
use Drush\Commands\DrushCommands;

class CivicthemeDrushCommands extends DrushCommands {

  /**
   * Color manager.
   *
   * @var \Drupal\civictheme\CivicthemeColorManager
   */
  protected $colorManager;

  /**
   * Prints the current alias name and info.
   *
   * @command civictheme:sbc
   */
  public function setBrandColors($light_brand1, $light_brand2, $light_brand3, $dark_brand1, $dark_brand2, $dark_brand3) {
    $this->colorManager = \Drupal::classResolver(CivicthemeColorManager::class);
    $this->colorManager->updateColors($light_brand1, $light_brand2, $light_brand3, $dark_brand1, $dark_brand2, $dark_brand3);
  }

}
