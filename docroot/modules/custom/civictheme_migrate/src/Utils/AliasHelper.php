<?php

namespace Drupal\civictheme_migrate\Utils;

/**
 * Alias Support helper class.
 *
 * @package Drupal\civictheme_migrate\Utils
 */
class AliasHelper {

  /**
   * Extract the domain.
   *
   * @param string $url
   *   The URL.
   *
   * @return string
   *   The clean domain with the scheme.
   */
  public static function extractDomainFromUrl(string $url) : string {
    $parsed_url = parse_url(trim($url));

    return $parsed_url['scheme'] . '://' . $parsed_url['host'];
  }

  /**
   * Extract the alias from the URL by sanitising and stripping the domain.
   *
   * @param string $url
   *   The URL.
   *
   * @return string
   *   The clean alias with path only and the query string (if exists).
   */
  public static function extractAliasFromUrl(string $url) : string {
    $parsed_url = parse_url(trim($url));
    if (!empty($parsed_url['path'])) {
      $alias = $parsed_url['path'];
      if (!empty($parsed_url['query'])) {
        $alias .= '?' . $parsed_url['query'];
      }
    }

    return $alias ?? $url;
  }

  /**
   * Extract the alias path from the URL by sanitising and stripping the domain.
   *
   * @param string $url
   *   The URL.
   *
   * @return string
   *   The clean alias with path only.
   */
  public static function extractAliasPathFromUrl(string $url) : string {
    $parsed_url = parse_url(trim($url));
    return $parsed_url['path'] ?? '';
  }

  /**
   * Check if a URI is internal.
   *
   * @param string $uri
   *   The URI.
   *
   * @return bool
   *   The result.
   */
  public static function isInternalUri(string $uri) : bool {
    return strpos($uri, 'internal:') === 0 || strpos($uri, '/') === 0;
  }

  /**
   * Extract the alias from an internal URI.
   *
   * @param string $uri
   *   The URI.
   *
   * @return string
   *   The alias.
   */
  public static function getAliasFromInternalUri(string $uri) : string {
    return static::isInternalUri($uri) ? preg_replace('/(^internal:)/i', '', $uri) : $uri;
  }

  /**
   * Prepend internal:/ scheme to an internal URI.
   *
   * @param string $uri
   *   The URI.
   *
   * @return string
   *   The standardised URI.
   */
  public static function standardiseInternalUri(string $uri) : string {
    if ((strpos($uri, '/') === 0)) {
      $uri = 'internal:' . $uri;
    }
    return $uri;
  }

  /**
   * Sanitise an alias.
   *
   * @param string $alias
   *   The alias.
   *
   * @return string
   *   The sanitised alias.
   */
  public static function sanitiseAlias(string $alias) : string {
    $value = trim($alias);
    return trim($value, '/');
  }

}
