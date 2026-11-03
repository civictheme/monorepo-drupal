<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Hook\Preprocess;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Layout preprocess hook implementations for CivicTheme.
 */
class CivicThemeStarterLayoutHooks {

  /**
   * Implements hook_preprocess_HOOK() for layout__three_columns.
   */
  #[Hook('preprocess_layout__three_columns')]
  public function preprocessLayoutThreeColumns(array &$variables): void {
    $variables['is_contained'] = $variables['settings']['is_contained'] ?? FALSE;
    $variables['vertical_spacing'] = $variables['settings']['vertical_spacing'] ?? 'auto';
  }

}
