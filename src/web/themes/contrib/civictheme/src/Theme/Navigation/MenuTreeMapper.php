<?php

declare(strict_types=1);

namespace Drupal\civictheme\Theme\Navigation;

use Drupal\Core\Url;

/**
 * Maps Drupal menu tree items onto CivicTheme navigation item structures.
 *
 * Internal to CivicTheme.
 */
final class MenuTreeMapper {

  /**
   * Add required element for external link.
   *
   * @param array $items
   *   Menu items to process.
   * @param bool $expand_all
   *   Whether to mark every item as expanded.
   *
   * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
   * @SuppressWarnings(PHPMD.ElseExpression)
   */
  public static function processMenuItems(array &$items, bool $expand_all = FALSE): void {
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
        static::processMenuItems($item['below'], $expand_all);
      }
    }
  }

}
