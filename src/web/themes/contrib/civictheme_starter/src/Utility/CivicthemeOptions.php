<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Utility;

use Drupal\civictheme_starter\CivicthemeConstants;

/**
 * Form API options helpers.
 */
final class CivicthemeOptions {

  /**
   * Theme options suitable for FormAPI.
   *
   * @param bool $only_keys
   *   Whether to return only keys or keys and labels.
   *
   * @return array<string, string>|array<string>
   *   Array of theme options.
   *
   * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
   */
  public static function themeOptions(bool $only_keys = FALSE): array {
    $map = [
      CivicthemeConstants::THEME_LIGHT => CivicthemeConstants::THEME_LIGHT_LABEL,
      CivicthemeConstants::THEME_DARK => CivicthemeConstants::THEME_DARK_LABEL,
    ];

    return $only_keys ? array_keys($map) : $map;
  }

  /**
   * Type options suitable for FormAPI.
   *
   * @return array<string, string>
   *   Array of type options.
   */
  public static function typeOptions(): array {
    return [
      'default' => 'Default',
      'stacked' => 'Stacked',
      'inline' => 'Inline',
      'inline_stacked' => 'Inline Stacked',
    ];
  }

}
