<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConfigManager;
use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\Navigation\MenuTreeMapper;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Menu preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class MenuHooks {

  /**
   * Implements template_preprocess_menu().
   */
  #[Hook('preprocess_menu')]
  public function preprocessMenu(array &$variables): void {
    MenuTreeMapper::processMenuItems($variables['items']);
  }

  /**
   * Implements template_preprocess_menu_local_tasks().
   */
  #[Hook('preprocess_menu_local_tasks')]
  public function preprocessMenuLocalTasks(array &$variables): void {
    $local_task_types = ['primary', 'secondary'];

    /** @var \Drupal\civictheme\CivicthemeConfigManager $theme_config_manager */
    $theme_config_manager = \Drupal::classResolver(CivicthemeConfigManager::class);
    $context_theme = $theme_config_manager->load('components.header.theme', CivicthemeConstants::HEADER_THEME_DEFAULT);

    foreach ($local_task_types as $local_task_type) {
      $variables['links'][$local_task_type] = [];
      foreach ($variables[$local_task_type] as $link) {
        if (!empty($link['#access']) && ($link['#access'] === TRUE || ($link['#access'] instanceof AccessResultInterface && $link['#access']->isAllowed()))) {
          $variables['links'][$local_task_type][] = [
            'theme' => $context_theme,
            'text' => $link['#link']['title'],
            'url' => $link['#link']['url']->toString(),
            'is_new_window' => FALSE,
            'is_external' => $link['#link']['url']->isExternal(),
            'modifier_class' => !empty($link['#active']) && $link['#active'] === TRUE ? 'ct-tabs__tab--selected selected' : '',
            'weight' => $link['#weight'] ?? 0,
          ];
        }
      }

      // Sort tabs by weight.
      $weights = array_column($variables['links'][$local_task_type], 'weight');
      array_multisort($weights, SORT_ASC, $variables['links'][$local_task_type]);
      unset($variables[$local_task_type]);
    }
  }

}
