<?php

/**
 * @file
 * Table theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_table().
 */
function civictheme_preprocess_table(array &$variables): void {
  if (!empty($variables['header'])) {
    $rows = [];
    foreach ($variables['header'] as $row) {
      $rows[] = $row['content'];
    }

    $variables['header'] = $rows;
  }

  // Rows, Footer.
  foreach (['rows', 'footer'] as $section) {
    $rows = [];

    if (!empty($variables[$section])) {
      foreach ($variables[$section] as $row_key => $row) {
        foreach ($row['columns'] as $cell) {
          $cell = $cell['content'] ?? '';
          $cell = (string) \Drupal::service('renderer')->render($cell);
          $cell = _civictheme_process__html_content($cell);
          $rows[$row_key][] = $cell;
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
