<?php

declare(strict_types=1);

namespace Drupal\civictheme\Utility;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Date and time formatting helpers.
 *
 * The procedural civictheme_format_datetime*() functions delegate here.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
final class CivicthemeDate {

  /**
   * Format date and time.
   *
   * This is a centralized helper for formatting dates in CivicTheme.
   *
   * @param int|string|\Drupal\Core\Datetime\DrupalDateTime $datetime
   *   Timestamp, date as a string or DrupalDateTime object.
   * @param string $format_or_type
   *   CivicTheme format type (starting with 'civictheme_') or actual PHP's
   *   date format string. Defaults to 'civictheme_short_date_and_time'.
   *
   * @return string
   *   Formatted date and time.
   */
  public static function format(int|string|DrupalDateTime $datetime, string $format_or_type = 'civictheme_short_date_and_time'): string {
    $type = 'custom';
    $format = $format_or_type;

    if (str_starts_with($format_or_type, 'civictheme_')) {
      $type = $format_or_type;
      $format = '';
    }

    if ($datetime instanceof DrupalDateTime) {
      $datetime = $datetime->getTimestamp();
    }
    elseif (is_string($datetime)) {
      $datetime = (int) strtotime($datetime);
    }

    return \Drupal::service('date.formatter')->format($datetime, $type, $format);
  }

  /**
   * Format date and time in ISO format.
   *
   * @param int|string|\Drupal\Core\Datetime\DrupalDateTime $datetime
   *   Timestamp, date as a string or DrupalDateTime object.
   *
   * @return string
   *   Formatted date and time.
   */
  public static function formatIso(int|string|DrupalDateTime $datetime): string {
    return self::format($datetime, 'c');
  }

}
