<?php

/**
 * @file
 * Link related functions.
 */

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;

/**
 * Process HTML links within an HTML content string.
 *
 * @param string $html
 *   The string to be processed.
 * @param string $base_url
 *   The hostname to process.
 * @param bool $new_window
 *   Flag to open all links in the new window.
 * @param bool $external_new_window
 *   Flag to open external links in the new window.
 * @param array $override_domains
 *   Array of override domains.
 *
 * @return string
 *   The processed HTML string.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_process_html_content_links($html, $base_url, $new_window = FALSE, $external_new_window = FALSE, array $override_domains = []) {
  $link_class = 'civictheme-link';
  $link_external_class = 'civictheme-link--external';

  $dom = Html::load($html);

  if (!$dom) {
    return $html;
  }

  $anchors = $dom->getElementsByTagName('a');
  if (!$anchors->count()) {
    return $html;
  }

  foreach ($anchors as $anchor) {
    $classes = $anchor->hasAttribute('class') ? ($anchor->getAttribute('class') ?: '') : '';

    if (!str_contains($classes, $link_class)) {
      $classes .= ' ' . $link_class;
    }

    if ($anchor->hasAttribute('href')) {
      $url = $anchor->getAttribute('href');
      $is_external = civictheme_url_is_external($url, $base_url, $override_domains);

      if (!$anchor->hasAttribute('target')) {
        if ($is_external) {
          $classes .= ' ' . $link_external_class;
        }

        if ($new_window || ($is_external && $external_new_window)) {
          $anchor->setAttribute('target', '_blank');
        }
      }
    }

    $classes = trim($classes);

    $anchor->setAttribute('class', $classes);
  }

  return Html::serialize($dom);
}

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
 *   TRUE if the link is external, FALSE otherwise.
 */
function civictheme_url_is_external($url, $base_url, array $override_domains = []) {
  if (UrlHelper::isExternal($url)) {
    $override_domains[] = $base_url;
    foreach ($override_domains as $override_domain) {
      $override_domain = _civictheme_external_link_normalise_domain($override_domain);

      if (!UrlHelper::isValid($override_domain, TRUE)) {
        continue;
      }

      if (UrlHelper::externalIsLocal($url, $override_domain)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  return FALSE;
}

/**
 * Normalise domain.
 */
function _civictheme_external_link_normalise_domain($domain) {
  // Check that the part of the domain before first dot has protocol and
  // add one if it does not.
  if (!str_contains(substr($domain, 0, strpos($domain, '.')), ':')) {
    $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
  }

  return $domain;
}
