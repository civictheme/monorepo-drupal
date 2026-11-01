<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook;

use Drupal\civictheme\CivicthemeColorRecipeManager;
use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Utility\CivicthemeAttributeHelper;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Component\Exception\ComponentNotFoundException;

/**
 * Theme-level hook implementations for CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class CivicthemeThemeHooks {

  /**
   * Implements hook_theme().
   *
   * @return array<string, array<string, mixed>>
   *   Theme definitions.
   */
  #[Hook('theme')]
  public function theme(): array {
    // Components from the library are used directly by including them within
    // existing templates.
    //
    // However, in some rare cases we still need to define custom themes to
    // allow component reuse outside the library.
    //
    // The list below may be reduced further upon architectural review and
    // adjustments within the library.
    return [
      'civictheme_basic_content' => [
        'variables' => [
          'component_theme' => CivicthemeConstants::THEME_LIGHT,
          'content' => '',
          'vertical_spacing' => NULL,
          'is_contained' => TRUE,
          'with_background' => FALSE,
          'attributes' => NULL,
          'modifier_class' => NULL,
        ],
        'template' => 'misc/basic-content',
      ],
      'civictheme_tag_list' => [
        'variables' => [
          'tags' => [],
          'vertical_spacing' => NULL,
          'content_top' => NULL,
          'content_bottom' => NULL,
          'modifier_class' => NULL,
        ],
        'template' => 'misc/tag-list',
      ],
      'civictheme_table_of_contents' => [
        'variables' => [
          'component_theme' => CivicthemeConstants::THEME_LIGHT,
          'title' => NULL,
          'position' => NULL,
          'links' => NULL,
          'anchor_selector' => NULL,
          'scope_selector' => NULL,
          'content' => NULL,
          'attributes' => NULL,
          'modifier_class' => '',
        ],
        'template' => 'misc/table-of-contents',
      ],
    ];
  }

  /**
   * Implements hook_theme_registry_alter().
   */
  #[Hook('theme_registry_alter')]
  public function themeRegistryAlter(array &$theme_registry): void {
    // Define a new preprocess callable that will always run last.
    $callable = static::class . '::preprocessLast';

    // The preprocessLast() callable converts
    // $variables['attributes']['class'] to $variables['modifier_class']
    // which civictheme components use to add in additional classes but if
    // the template is not a civictheme component then we do not want to do
    // this because Drupal has no concept of this.
    foreach ($theme_registry as $name => $info) {
      if (isset($info['path']) && (str_starts_with($info['path'], 'core') || str_starts_with($info['path'], 'module'))) {
        continue;
      }
      if (!in_array($callable, $theme_registry[$name]['preprocess functions'] ?? [])) {
        $theme_registry[$name]['preprocess functions'][] = $callable;
        continue;
      }
      foreach ($info['preprocess functions'] as $k => $function) {
        if ($function == $callable) {
          unset($theme_registry[$name]['preprocess functions'][$k]);
          $theme_registry[$name]['preprocess functions'][] = $callable;
        }
      }
    }
  }

  /**
   * Preprocess callable registered to always run last for all theme hooks.
   *
   * @see self::themeRegistryAlter()
   */
  public static function preprocessLast(array &$variables): void {
    // Process attributes for all components before they are sent to
    // templates.
    CivicthemeAttributeHelper::convertAttributesToModifierClass($variables);
  }

  /**
   * Implements hook_library_info_alter().
   *
   * Alter libraries to attach the colour recipe overrides stylesheet.
   *
   * The base `css-variables` stylesheet (dist/civictheme.variables.css)
   * carries the UI Kit token custom properties and always loads. When the
   * Colour Picker is enabled and a recipe has been saved, the overrides CSS
   * emitted from the recipe is written to a public file and appended after
   * the base stylesheet so the recipe values win the cascade.
   *
   * This hook runs in a context of the extension that provides the library.
   * For themes, this means that a parent theme may provide a library used by
   * a sub-theme.
   *
   * We are using this hook to implement certain library adjustments on
   * behalf of sub-themes so that they do not have to implement this logic.
   */
  #[Hook('library_info_alter')]
  public function libraryInfoAlter(array &$libraries, string $extension): void {
    // Filter for the current theme or a parent theme (in case if current
    // theme is a sub-theme of a theme that declares libraries).
    $themes = \Drupal::service('theme_handler')->listInfo();
    if (!array_key_exists($extension, $themes)) {
      return;
    }

    // If there is no declaration of our library - there is nothing for us to
    // do so we exit early.
    if (empty($libraries['css-variables'])) {
      return;
    }

    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);

    // Only apply if the Colour Picker was explicitly enabled in
    // configuration.
    if (!$theme_config_manager->load('colors.use_color_selector', FALSE)) {
      return;
    }

    /** @var \Drupal\civictheme\CivicthemeColorRecipeManager $color_recipe_manager */
    $color_recipe_manager = \Drupal::classResolver(CivicthemeColorRecipeManager::class);

    // Generate a stylesheet suffixed with the current theme name. Generation
    // may be skipped if no overrides CSS was stored - only proceed if we
    // have a valid stylesheet path.
    $active_theme = \Drupal::theme()->getActiveTheme();
    $stylesheet_path = $color_recipe_manager->stylesheet($active_theme->getName());

    if ($stylesheet_path !== '') {
      $libraries['css-variables']['css']['theme'][$stylesheet_path] = [
        'preprocess' => FALSE,
        'weight' => 11,
      ];
    }
  }

  /**
   * Implements hook_page_attachments_alter().
   */
  #[Hook('page_attachments_alter')]
  public function pageAttachmentsAlter(array &$attachments): void {
    $current_user = \Drupal::currentUser();
    if ($current_user->isAuthenticated()) {
      $attachments['#attached']['library'][] = 'civictheme/admin';
    }
    /** @var \Drupal\Core\Theme\ComponentPluginManager $component_plugin_manager */
    $component_plugin_manager = \Drupal::service('plugin.manager.sdc');
    try {
      $alert = $component_plugin_manager->find('civictheme:alert');
      $attachments['#attached']['library'][] = $alert->getLibraryName();
    }
    catch (ComponentNotFoundException $exception) {
      \Drupal::logger('civictheme')->error('Unable to find alert component: %message', ['%message' => $exception->getMessage()]);
    }
    $attachments['#cache']['contexts'] = Cache::mergeContexts($attachments['#cache']['contexts'] ?? [], ['url.query_args']);
  }

}
