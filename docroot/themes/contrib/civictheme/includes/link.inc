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
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
function _civictheme_process_html_content_links($html, $base_url, $new_window = FALSE, $external_new_window = FALSE, array $override_domains = []) {
  $class_prefix = 'ct-';
  $link_class = 'ct-content-link ct-theme-light';
  $link_external_class = 'ct-content-link--external';

  $dom = Html::load($html);

  if (!$dom) {
    return $html;
  }

  $anchors = $dom->getElementsByTagName('a');
  if (!$anchors->count()) {
    return $html;
  }

  foreach ($anchors as $anchor) {
    // Apply only to anchors with links.
    if (!$anchor->hasAttribute('href')) {
      continue;
    }

    // Apply only if there are no classes prefixed with a prefix.
    $classes = $anchor->hasAttribute('class') ? ($anchor->getAttribute('class') ?: '') : '';
    if (str_contains($classes, $class_prefix)) {
      continue;
    }

    $url = $anchor->getAttribute('href');
    $is_external = civictheme_url_is_external($url, $base_url, $override_domains);

    if (!str_contains($classes, $link_class)) {
      $classes .= ' ' . $link_class;
    }

    if ($is_external && !str_contains($classes, $link_external_class)) {
      $classes .= ' ' . $link_external_class;
    }

    if (!$anchor->hasAttribute('target')) {
      if ($new_window || ($is_external && $external_new_window)) {
        $anchor->setAttribute('target', '_blank');
      }
    }

    $anchor->setAttribute('class', trim($classes));
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
 *   TRUE if the provided URL is external, FALSE if not external or not a valid
 *   URL.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_url_is_external($url, $base_url, array $override_domains = []) {
  if (empty($url) || !UrlHelper::isValid($url)) {
    return FALSE;
  }

  $url_parts = parse_url($url);
  if (!empty($url_parts['scheme']) && empty($url_parts['host'])) {
    return FALSE;
  }

  if (UrlHelper::isExternal($url)) {
    $override_domains[] = $base_url;
    foreach ($override_domains as $override_domain) {
      $override_domain = _civictheme_external_link_normalize_domain($override_domain);

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
function _civictheme_external_link_normalize_domain($domain) {
  // Check that the part of the domain before first dot has protocol and
  // add one if it does not.
  if (!str_contains(substr($domain, 0, strpos($domain, '.')), ':')) {
    $domain = 'https://' . (str_starts_with($domain, '//') ? str_replace('//', '', $domain) : $domain);
  }

  return $domain;
}

/**
 * Process HTML emails within an HTML content string.
 *
 * @param string $html
 *   The string to be processed.
 *
 * @return string
 *   The processed HTML string.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_process_html_content_emails($html) {
  // Tags to skip and not recurse into.
  $ignore_tags = 'a|script|style|code|pre';
  // Match email addresses.
  // Mail domains differ from the generic domain pattern, specifically:
  // A . character must be present in the string that follows the @ character.
  $email_domain = '(?:[\p{L}\p{M}\p{N}._+-]+\.)+[\p{L}\p{M}]{2,64}\b';
  $url_pattern = "[\p{L}\p{M}\p{N}._+-]{1,254}@(?:$email_domain)";
  $pattern = "`($url_pattern)`u";

  // HTML comments need to be handled separately, as they may contain HTML
  // markup, especially a '>'. Therefore, remove all comment contents and add
  // them back later.
  _filter_url_escape_comments('', TRUE);
  $html = preg_replace_callback('`<!--(.*?)-->`s', '_filter_url_escape_comments', $html);

  // Split at all tags; ensures that no tags or attributes are processed.
  $chunks = preg_split('/(<.+?>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
  // PHP ensures that the array consists of alternating delimiters and
  // literals, and begins and ends with a literal (inserting NULL as
  // required). Therefore, the first chunk is always text:
  $chunk_type = 'text';
  // If a tag of $ignore_tags is found, it is stored in $open_tag and only
  // removed when the closing tag is found. Until the closing tag is found,
  // no replacements are made.
  $open_tag = '';

  for ($i = 0; $i < count($chunks); $i++) {
    if ($chunk_type == 'text') {
      // Only process this text if there are no unclosed $ignore_tags.
      if ($open_tag == '') {
        // If there is a match, inject a link into this chunk via the callback
        // function contained in $task.
        $chunks[$i] = preg_replace_callback($pattern, '_filter_url_parse_email_links', $chunks[$i]);
      }
      // Text chunk is done, so next chunk must be a tag.
      $chunk_type = 'tag';
    }
    else {
      // Only process this tag if there are no unclosed $ignore_tags.
      if ($open_tag == '') {
        // Check whether this tag is contained in $ignore_tags.
        if (preg_match("`<($ignore_tags)(?:\s|>)`i", $chunks[$i], $matches)) {
          $open_tag = $matches[1];
        }
      }
      // Otherwise, check whether this is the closing tag for $open_tag.
      else {
        if (preg_match("`<\/$open_tag>`i", $chunks[$i], $matches)) {
          $open_tag = '';
        }
      }
      // Tag chunk is done, so next chunk must be text.
      $chunk_type = 'text';
    }
  }

  $html = implode($chunks);
  // Revert to the original comment contents.
  _filter_url_escape_comments('', FALSE);
  $html = preg_replace_callback('`<!--(.*?)-->`', '_filter_url_escape_comments', $html);

  return $html;
}
