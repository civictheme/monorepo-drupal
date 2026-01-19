<?php

/**
 * @file
 * Table theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_table().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_preprocess_table(array &$variables): void {
  if (!empty($variables['header'])) {
    $rows = [];
    foreach ($variables['header'] as $row) {
      $rows[] = $row['content'];
    }

    $variables['header'] = $rows;

    // Create sanitised header array with HTML stripped.
    $header_sanitized = [];
    foreach ($variables['header'] as $header_item) {
      if (!empty($header_item) && !is_array($header_item) && get_class($header_item) == 'Drupal\Core\StringTranslation\TranslatableMarkup') {
        // Ensure correct render structure.
        $header_item = ['#title' => $header_item];
      }
      $rendered = (string) \Drupal::service('renderer')->render($header_item);
      // Set to empty string if visually-hidden class is present.
      $has_visually_hidden = preg_match('/class=["\'][^"\']*\b(?:visually-hidden|ct-visually-hidden)\b[^"\']*["\']/', $rendered);
      $header_sanitized[] = $has_visually_hidden ? '' : strip_tags($rendered);
    }
    $variables['header_sanitized'] = $header_sanitized;
  }

  // Rows, Footer.
  foreach (['rows', 'footer'] as $section) {
    $rows = [];

    if (!empty($variables[$section])) {
      foreach ($variables[$section] as $row_key => $row) {
        $row_cells = $row['cells'] ?? $row['columns'] ?? [];
        foreach ($row_cells as $cell) {
          $cell = $cell['content'] ?? '';
          $cell = civictheme_render($cell);
          $cell = _civictheme_process__html_content($cell);
          $rows[$row_key][] = [
            '#type' => 'markup',
            '#markup' => $cell,
          ];
        }
      }
    }

    $variables[$section] = $rows;
  }
}

/**
 * Implements template_preprocess_HOOK() for views_view_table.
 */
function civictheme_preprocess_views_view_table(array &$variables): void {
  if (_civictheme_feature_is_optedout('views', CivicthemeConstants::OPTOUT_VIEWS_STYLE_TABLE, $variables['view'])) {
    $variables['civictheme_component_optout'] = TRUE;

    return;
  }

  civictheme_preprocess_table($variables);
}
