<?php

/**
 * @file
 * CivicTheme libraries processing.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeColorRecipeManager;
use Drupal\civictheme\CivicthemeConfigManager;

/**
 * Alter libraries to attach the colour recipe overrides stylesheet.
 *
 * The base `css-variables` stylesheet (dist/civictheme.variables.css) carries
 * the UI Kit token custom properties and always loads. When the Colour Picker
 * is enabled and a recipe has been saved, the overrides CSS emitted from the
 * recipe is written to a public file and appended after the base stylesheet
 * so the recipe values win the cascade.
 *
 * This hook runs in a context of the extension that provides the library.
 * For themes, this means that a parent theme may provide a library used by
 * a sub-theme.
 *
 * We are using this hook to implement certain library adjustments on behalf
 * of sub-themes so that they do not have to implement this logic.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_library_info_alter__color_selector(array &$libraries, string $extension): void {
  // Filter for the current theme or a parent theme (in case if current theme
  // is a sub-theme of a theme that declares libraries).
  $themes = \Drupal::service('theme_handler')->listInfo();
  if (!array_key_exists($extension, $themes)) {
    return;
  }

  // If there is no declaration of our library - there is nothing for us to do
  // so we exit early.
  if (empty($libraries['css-variables'])) {
    return;
  }

  /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
  $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

  // Only apply if the Colour Picker was explicitly enabled in configuration.
  if (!$theme_config_manager->load('colors.use_color_selector', FALSE)) {
    return;
  }

  /** @var \Drupal\civictheme\CivicthemeColorRecipeManager $color_recipe_manager */
  $color_recipe_manager = \Drupal::classResolver(CivicthemeColorRecipeManager::class);

  // Generate a stylesheet suffixed with the current theme name. Generation
  // may be skipped if no overrides CSS was stored - only proceed if we have
  // a valid stylesheet path.
  $active_theme = \Drupal::theme()->getActiveTheme();
  $stylesheet_path = $color_recipe_manager->stylesheet($active_theme->getName());

  if ($stylesheet_path !== '') {
    $libraries['css-variables']['css']['theme'][$stylesheet_path] = [
      'preprocess' => FALSE,
      'weight' => 11,
    ];
  }
}
