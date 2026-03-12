<?php

/**
 * @file
 * Table theme alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Template\Attribute;

/**
 * Implements template_preprocess_table().
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ElseExpression)
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
      if (!empty($header_item) && !is_array($header_item) && $header_item instanceof TranslatableMarkup) {
        // Ensure correct render structure.
        $header_item = ['#title' => $header_item];
      }
      $header_item = $header_item ?? '';
      // Renderer::render() requires an array in Drupal 11.3+;.
      if (is_array($header_item)) {
        $rendered = (string) \Drupal::service('renderer')->render($header_item);
      }
      else {
        $rendered = (string) $header_item;
      }
      // Set to empty string if visually-hidden class is present.
      $has_visually_hidden = preg_match('/class=["\'][^"\']*\b(?:visually-hidden|ct-visually-hidden)\b[^"\']*["\']/', $rendered);
      $header_sanitized[] = $has_visually_hidden ? '' : strip_tags($rendered);
    }
    $variables['header_sanitized'] = $header_sanitized;
  }

  // Rows, Footer - preserve row and cell attributes for table drag and other
  // Drupal behaviors (e.g. Layout Builder block order).
  foreach (['rows', 'footer'] as $section) {
    $rows = [];

    if (!empty($variables[$section])) {
      foreach ($variables[$section] as $row_key => $row) {
        $row_cells = $row['cells'] ?? $row['columns'] ?? [];
        $row_attrs = $row['attributes'] ?? [];
        $rows[$row_key] = [
          // Store as array so Element::children() does not receive an object.
          'attributes' => $row_attrs instanceof Attribute ? $row_attrs->toArray() : (array) $row_attrs,
        ];
        foreach ($row_cells as $cell) {
          $cell_content = is_array($cell) ? ($cell['content'] ?? $cell['data'] ?? '') : $cell;
          $cell_attributes = is_array($cell) && isset($cell['attributes']) && $cell['attributes'] instanceof Attribute
            ? clone $cell['attributes']
            : new Attribute(is_array($cell) && isset($cell['attributes']) ? $cell['attributes'] : []);
          $cell_content = civictheme_render($cell_content);
          $cell_content = _civictheme_process__html_content($cell_content);
          $rows[$row_key]['cols'][] = [
            'content' => [
              '#type' => 'markup',
              '#markup' => $cell_content,
            ],
            // Store as array so Element::children() does not receive an object.
            'attributes' => $cell_attributes->toArray(),
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
