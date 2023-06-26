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
   * Extract local URL from provided URI.
   *
   * This is a helper method expected to be called from the getUrl() method of
   * the implementing class.
   *
   * @param string $uri
   *   URI to process.
   * @param array $local_domains
   *   Array of domains that should be considered local.
   *
   * @return string|null
   *   The URL or NULL if not found.
   */
  public static function extractLocalUrl(string $uri, array $local_domains = []): ?string {
    if (empty($uri)) {
      return NULL;
    }

    if (static::isAnchor($uri) || static::isMailto($uri)) {
      return NULL;
    }

    if (static::isExternal($uri)) {
      foreach ($local_domains as $local_domain) {
        if (!static::isValid($local_domain, TRUE)) {
          continue;
        }

        if (static::externalIsLocal($uri, $local_domain)) {
          return static::sanitiseRelativeUrl($uri);
        }
      }

      return NULL;
    }

    $uri = static::sanitiseRelativeUrl($uri);

    return $uri;
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
   * Check if a URL is a 'mailto:'.
   *
   * @param string $url
   *   The URL to check.
   *
   * @return bool
   *   TRUE if an email link.
   */
  public static function isMailto(string $url): bool {
    return str_starts_with($url, 'mailto:');
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
    $parsed_url = parse_url($url);
    $url = $parsed_url['path'];

    // Ensure the URL only start with a single /.
    $url = '/' . ltrim($url, '/');

    if (strpos($url, '+') !== FALSE) {
      $url = urldecode($url);
    }
    elseif (strpos($url, '%') !== FALSE) {
      $url = rawurldecode($url);
    }

    return $url;
  }

}
