<?php

/**
 * @file
 * Theme generic process functions.
 */

declare(strict_types=1);

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
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_process__html_content(string $html, array $options = []): string {
  // Process links.
  $html = _civictheme_process_html_content_urls_to_links($html);

  $link_settings = civictheme_get_theme_config_manager()->load('components.link', []);
  $request = \Drupal::service('request_stack')->getCurrentRequest();
  $base_url = $request->getHost() . (empty($request->getPort()) ? '' : ':' . $request->getPort());

  $html = _civictheme_process_html_content_links(
    $html,
    $base_url,
    (bool) ($link_settings['new_window'] ?? FALSE),
    (bool) ($link_settings['external_new_window'] ?? FALSE),
    $link_settings['external_override_domains'] ?? []
  );

  // Process contextual theme value.
  if (!empty($options['theme'])) {
    // Replace theme variable with component level component theme.
    // Note: All style dropdowns and components need to use CivicTheme's
    // default theme light in order to be updated with the correct component
    // set theme.
    // Example: Button style dropdown classes are:
    // .ct-button.ct-theme-light.ct-button--primary.ct-button--regular
    // if the component is dark we str_replace the theme class and replace.
    // @todo We may need to implement this in a different way.
    $html = str_replace('ct-theme-light', 'ct-theme-' . $options['theme'], (string) $html);
  }

  return $html;
}

/**
 * Preprocess date range field.
 */
function _civictheme_preprocess_date_range_field(array &$variables): void {
  if ($variables['field_name'] !== 'field_c_n_date_range') {
    return;
  }

  $variables['node'] = $variables['element']['#object'] ?? NULL;
  _civictheme_preprocess_paragraph__node_field__date_range($variables);
  unset($variables['node']);
}
