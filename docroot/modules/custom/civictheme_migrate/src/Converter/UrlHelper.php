<?php

namespace Drupal\civictheme_migrate\Converter;

use Drupal\Component\Utility\UrlHelper as ParentUrlHelper;

/**
 * Class UrlHelper.
 *
 * Extends the core UrlHelper class.
 */
class UrlHelper extends ParentUrlHelper {

  /**
   * Check if a URL is relative (no scheme).
   *
   * @param string $url
   *   The URL to check.
   *
   * @return bool
   *   TRUE if relative.
   */
  public static function isRelativeUrl(string $url): bool {
    return !static::isExternal($url);
  }

  /**
   * Check if a URL is an anchor.
   *
   * @param string $url
   *   The URL to check.
   *
   * @return bool
   *   TRUE if an anchor.
   */
  public static function isAnchor(string $url): bool {
    return str_starts_with($url, '#');
  }

  /**
   * Sanitise a relative URL.
   *
   * @param string $url
   *   The URL to sanitise.
   *
   * @return string
   *   The sanitised URL.
   */
  public static function sanitiseRelativeUrl(string $url): string {
    if (static::isRelativeUrl($url)) {
      // Ensure the URL only start with a single /.
      $url = '/' . ltrim($url, '/');
      if (strpos($url, '+') !== FALSE) {
        $url = urldecode($url);
      }
      elseif (strpos($url, '%') !== FALSE) {
        $url = rawurldecode($url);
      }

      // Remove all query params except ID.
      $parsed_url = parse_url($url);
      if (!empty($parsed_url['path'])) {
        $url = $parsed_url['path'];
        if (!empty($parsed_url['query'])) {
          parse_str($parsed_url['query'], $query);
          if (!empty($query['ID'])) {
            $url .= '?ID=' . $query['ID'];
          }
        }
      }
    }

    return $url;
  }

}
