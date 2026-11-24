<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook;

use Drupal\civictheme\CivicthemeColorRecipeManager;
use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Utility\CivicthemeAttribute;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Component\Exception\ComponentNotFoundException;

/**
 * Theme-level hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
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
    // Attach suggestion preprocess implementations to all matching registry
    // entries. Core only attaches a suggestion preprocess hook to its
    // exact-name entry, and suggestion inheritance walks prefixes only
    // (node__BUNDLE__VIEW_MODE inherits from node__BUNDLE and node, never
    // from node__VIEW_MODE), so an entry created by a bundle-specific
    // template would silently drop the view-mode preprocess without this.
    // This also discovers sub-theme implementations across the theme chain.
    $theme_names = $this->themeChainNames();
    $this->registerNodeSuggestionPreprocess($theme_registry, $theme_names);
    $this->registerSuggestionPreprocess($theme_registry, 'paragraph', $theme_names);
    $this->registerSuggestionPreprocess($theme_registry, 'block', $theme_names);

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
   * Gets all theme names in the active theme chain, base themes first.
   *
   * @return list<string>
   *   Machine names of the base themes followed by the active theme.
   */
  protected function themeChainNames(): array {
    $active_theme = \Drupal::theme()->getActiveTheme();
    $theme_names = array_keys($active_theme->getBaseThemeExtensions());
    $theme_names[] = $active_theme->getName();

    return $theme_names;
  }

  /**
   * Checks whether a preprocess implementation exists under a function name.
   *
   * Covers both procedural functions and class-based #[Hook] implementations:
   * the registry's 'preprocess invokes' map holds the virtual function names
   * core assigned to class-based suggestion preprocess hooks, and is the
   * exact lookup the theme manager uses to invoke them at runtime.
   *
   * @param array $theme_registry
   *   The theme registry being altered.
   * @param string $function
   *   Function name to check, e.g. 'civictheme_preprocess_node__full'.
   */
  protected function preprocessImplementationExists(array $theme_registry, string $function): bool {
    return isset($theme_registry['preprocess invokes'][$function]) || function_exists($function);
  }

  /**
   * Registers node view-mode preprocess implementations on node suggestions.
   *
   * For each node suggestion entry (e.g.
   * node__civictheme_page__civictheme_promo_card), registers the view-mode
   * preprocess implementation (e.g.
   * civictheme_preprocess_node__civictheme_promo_card) and, for
   * bundle+view_mode entries, the full-name implementation
   * (e.g. civictheme_preprocess_node__civictheme_page__civictheme_promo_card)
   * when one exists in any theme in the chain.
   *
   * @param array $theme_registry
   *   The theme registry being altered.
   * @param list<string> $theme_names
   *   Theme machine names in the chain, base themes first.
   */
  protected function registerNodeSuggestionPreprocess(array &$theme_registry, array $theme_names): void {
    foreach ($theme_registry as $name => $info) {
      if (!is_string($name) || !str_starts_with($name, 'node__') || ($info['base hook'] ?? NULL) !== 'node') {
        continue;
      }

      $parts = explode('__', $name);
      // The view mode is the last part of entries like node__bundle__view_mode.
      $view_mode = end($parts);

      foreach ($theme_names as $theme_name) {
        $candidates = [$theme_name . '_preprocess_node__' . $view_mode];
        if (count($parts) >= 3) {
          $candidates[] = $theme_name . '_preprocess_' . $name;
        }
        $this->appendPreprocessCandidates($theme_registry, $name, $candidates);
      }
    }
  }

  /**
   * Registers suggestion preprocess implementations on suggestion entries.
   *
   * Builds all intermediate names from the suggestion parts: for
   * 'paragraph__civictheme_content__preview' this checks
   * THEME_preprocess_paragraph__civictheme_content and
   * THEME_preprocess_paragraph__civictheme_content__preview for every theme
   * in the chain.
   *
   * @param array $theme_registry
   *   The theme registry being altered.
   * @param string $base_hook
   *   The base hook to process suggestions of, e.g. 'paragraph' or 'block'.
   * @param list<string> $theme_names
   *   Theme machine names in the chain, base themes first.
   */
  protected function registerSuggestionPreprocess(array &$theme_registry, string $base_hook, array $theme_names): void {
    $prefix = $base_hook . '__';
    foreach ($theme_registry as $name => $info) {
      if (!is_string($name) || !str_starts_with($name, $prefix) || ($info['base hook'] ?? NULL) !== $base_hook) {
        continue;
      }

      $parts = explode('__', $name);
      foreach ($theme_names as $theme_name) {
        $candidates = [];
        $accumulated = $parts[0];
        $counter = count($parts);
        for ($i = 1; $i < $counter; $i++) {
          $accumulated .= '__' . $parts[$i];
          $candidates[] = $theme_name . '_preprocess_' . $accumulated;
        }
        $this->appendPreprocessCandidates($theme_registry, $name, $candidates);
      }
    }
  }

  /**
   * Appends preprocess candidates that exist to a registry entry.
   *
   * @param array $theme_registry
   *   The theme registry being altered.
   * @param string $name
   *   The registry entry name to append to.
   * @param list<string> $candidates
   *   Candidate preprocess function names.
   */
  protected function appendPreprocessCandidates(array &$theme_registry, string $name, array $candidates): void {
    foreach ($candidates as $candidate) {
      if ($this->preprocessImplementationExists($theme_registry, $candidate) && !in_array($candidate, $theme_registry[$name]['preprocess functions'] ?? [])) {
        $theme_registry[$name]['preprocess functions'][] = $candidate;
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
    CivicthemeAttribute::convertAttributesToModifierClass($variables);
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
    catch (ComponentNotFoundException $componentNotFoundException) {
      \Drupal::logger('civictheme')->error('Unable to find alert component: %message', ['%message' => $componentNotFoundException->getMessage()]);
    }
    $attachments['#cache']['contexts'] = Cache::mergeContexts($attachments['#cache']['contexts'] ?? [], ['url.query_args']);
  }

}
