<?php

/**
 * @file
 * Menu related functions.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\Url;

/**
 * Add required element for external link.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_menu_items(array &$items, bool $expand_all = FALSE): void {
  foreach ($items as &$item) {
    $item += [
      'is_new_window' => $item['url'] instanceof Url ? $item['url']->isExternal() : FALSE,
      'is_external' => $item['url'] instanceof Url ? $item['url']->isExternal() : FALSE,
    ];

    // Set as expanded based on $expand_all or item's own value or FALSE.
    $item['is_expanded'] = $expand_all || ($item['is_expanded'] ?? FALSE);
    // Initialize 'below' as an empty array if not set.
    $item['below'] = $item['below'] ?? [];
    if (isset($item['title'])) {
      $item['title'] = is_string($item['title']) ? strip_tags($item['title']) : $item['title'];
    }
    else {
      $item['title'] = '';
    }

    if (!empty($item['below'])) {
      _civictheme_preprocess_menu_items($item['below'], $expand_all);
    }
  }
}

/**
 * Implements template_preprocess_block__HOOK() for all menu_block blocks.
 *
 * Dispatches to per-menu preprocess functions based on the menu name or
 * suggestion config. This function is auto-discovered by Drupal's registry
 * builder, which creates the block__menu_block entry — eliminating the need
 * to register multi-level menu_block suggestions explicitly.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_block__menu_block(array &$variables): void {
  $menu_name = strtr($variables['elements']['#derivative_plugin_id'] ?? '', '-', '_');

  // Allow the menu_block 'suggestion' config to override the menu name.
  $suggestion = $variables['elements']['#configuration']['suggestion'] ?? '';
  if (!empty($suggestion)) {
    $menu_name = $suggestion;
  }

  $callback = '_civictheme_preprocess_block__menu_block__' . $menu_name;
  if (function_exists($callback)) {
    $callback($variables);
  }
}

/**
 * Preprocess for footer navigation menu block.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_block__menu_block__civictheme_footer(array &$variables): void {
  $theme = civictheme_get_theme_config_manager()->load('components.footer.theme', CivicthemeConstants::FOOTER_THEME_DEFAULT);
  $items = $variables['content']['#items'] ?? [];
  $title = $variables['configuration']['label_display'] ? $variables['configuration']['label'] : '';
  $modifier_class = civictheme_extract_modifier_class($variables);

  _civictheme_preprocess_menu_items($items);

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:navigation',
    // Override default empty string to prevent SDC injecting it as a prop.
    '#variant' => NULL,
    '#props' => array_filter([
      'theme' => $theme,
      'items' => $items ?: NULL,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => $title ? ['#markup' => $title] : NULL,
    ]),
  ];
}
