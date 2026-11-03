<?php

declare(strict_types=1);

namespace Drupal\civictheme\Utility;

use Drupal\Component\Utility\UrlHelper;

/**
 * URL and external link helpers.
 *
 * The procedural civictheme_url_*() functions delegate here.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
final class CivicthemeLink {

  /**
   * Check if the provided link is external.
   *
   * @param string $url
   *   The url to be validated.
   * @param string $base_url
   *   The base URL string to check against, such as "http://example.com/".
   * @param array $override_domains
   *   Array of overridden domains.
   *
   * @return bool
   *   TRUE if the provided URL is external, FALSE if not external or not a
   *   valid URL.
   */
  public static function urlIsExternal(string $url, string $base_url, array $override_domains = []): bool {
    if (!self::urlIsValid($url)) {
      return FALSE;
    }

    if (!UrlHelper::isExternal($url)) {
      return FALSE;
    }

    $override_domains[] = $base_url;
    foreach ($override_domains as $override_domain) {
      $override_domain = self::normalizeDomain($override_domain);

      if (!self::urlIsValid($override_domain, TRUE)) {
        continue;
      }

      try {
        if (UrlHelper::externalIsLocal($url, $override_domain)) {
          return FALSE;
        }
      }
      catch (\InvalidArgumentException) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * Check if a URL is valid.
   *
   * @param string $url
   *   The URL to be checked.
   * @param bool $absolute
   *   Whether the URL is absolute (beginning with a scheme such as "http:").
   *
   * @return bool
   *   TRUE if the provided URL is valid.
   *
   * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
   */
  public static function urlIsValid(string $url, bool $absolute = FALSE): bool {
    if (empty($url) || !UrlHelper::isValid($url, $absolute)) {
      return FALSE;
    }

    $url_parts = parse_url($url);

    return empty($url_parts['scheme']) || !empty($url_parts['host']);
  }

  /**
   * Normalise domain.
   *
   * @param string $domain
   *   The domain to be normalised.
   *
   * @return string
   *   Normalised domain.
   */
  public static function normalizeDomain(string $domain): string {
    // Check that the part of the domain before first dot has protocol and
    // add one if it does not.
    if (!str_contains(substr($domain, 0, strpos($domain, '.') ?: 0), ':')) {
      $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
    }

    return $domain;
  }

}
