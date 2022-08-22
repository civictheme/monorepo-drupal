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
function civictheme_process_html_content($html, $options = []) {
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

  // Process contextual theme value.
  if (!empty($options['theme'])) {
    // Replace theme variable with component level component theme.
    // Note: All style dropdowns and components need to use civictheme's
    // default theme light in order to be updated with the correct component
    // set theme.
    // Example: Button style dropdown classes are:
    // .civictheme-button.civictheme-theme-light.civictheme-button--primary.civictheme-button--regular
    // if the component is dark we str_replace the theme class and replace.
    // @todo We may need to implement this in a different way.
    $html = str_replace('civictheme-theme-light', 'civictheme-theme-' . $options['theme'], $html);
  }

  return $html;
}
