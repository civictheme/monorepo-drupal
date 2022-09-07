<?php

/**
 * @file
 * Table theme alterations.
 */

/**
 * Implements template_preprocess_table().
 */
function civictheme_preprocess_table(&$variables) {

  // Header.
  if (!empty($variables['header'])) {
    $rows = NULL;
    foreach ($variables['header'] as $row) {
      $rows[] = $row['content'];
    }
    $variables['header'] = $rows;
  }

  // Rows, Footer.
  foreach (['rows', 'footer'] as $section) {
    $rows = NULL;

    if (!empty($variables[$section])) {
      foreach ($variables[$section] as $row_key => $row) {
        foreach ($row['cells'] as $cell) {
          $rows[$row_key][] = $cell['content'];
        }
      }
    }

    $variables[$section] = $rows;
  }
}

/**
 * Implements template_preprocess_HOOK() for views_view_table.
 */
function civictheme_preprocess_views_view_table(&$variables) {
  // Header
  if (!empty($variables['header'])) {
    $rows = NULL;
    foreach ($variables['header'] as $row) {
      $rows[] = $row['content'];
    }
    $variables['header'] = $rows;
  }

  // Rows, Footer.
  foreach (['rows', 'footer'] as $section) {
    $rows = NULL;

    if (!empty($variables[$section])) {
      foreach ($variables[$section] as $row_key => $row) {
        foreach ($row['columns'] as $cell) {
          $rows[$row_key][] = $cell['content'];
        }
      }
    }

    $variables[$section] = $rows;
  }
}
