<?php

/**
 * @file
 * Theme generic process functions.
 */

/**
 * Process an HTML content string by applying several processors.
 *
 * Use this on all rendered HTML content fields to additionally process content.
 *
 * @param string $html
 *   The string to be processed.
 *
 * @return string
 *   The processed string.
 */
function civictheme_process_html_content($html) {
  $request = \Drupal::service('request_stack')->getCurrentRequest();
  $base_url = $request->getHost() . (!empty($request->getPort()) ? ':' . $request->getPort() : '');

  // Process links.
  $link_settings = theme_get_setting('components.link') ?? [];
  $html = _civictheme_process_html_content_links(
    $html,
    $base_url,
    $link_settings['new_window'] ?? FALSE,
    $link_settings['external_new_window'] ?? FALSE,
    $link_settings['external_override_domains'] ?? []
  );

  return $html;
}
