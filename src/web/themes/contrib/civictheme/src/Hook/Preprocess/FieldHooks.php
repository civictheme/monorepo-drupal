<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\CivicthemeUtility;
use Drupal\civictheme\Theme\FeatureOptout;
use Drupal\civictheme\Theme\HtmlContentProcessor;
use Drupal\civictheme\Theme\Paragraph\FieldMapper;
use Drupal\civictheme\Utility\CivicthemeField;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Element;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Template\Attribute;

/**
 * Field and table preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class FieldHooks {

  /**
   * Implements template_preprocess_field().
   */
  #[Hook('preprocess_field')]
  public function preprocessField(array &$variables): void {
    $this->preprocessWysiwygField($variables);
    $this->preprocessDateRangeField($variables);
  }

  /**
   * Implements template_preprocess_table().
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   * @SuppressWarnings("PHPMD.NPathComplexity")
   * @SuppressWarnings("PHPMD.ElseExpression")
   */
  #[Hook('preprocess_table')]
  public function preprocessTable(array &$variables): void {
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
        $rendered = CivicthemeUtility::render($header_item);
        // Set to empty string if visually-hidden class is present.
        $has_visually_hidden = preg_match('/class=["\'][^"\']*\b(?:visually-hidden|ct-visually-hidden)\b[^"\']*["\']/', $rendered);
        $header_sanitized[] = $has_visually_hidden ? '' : strip_tags($rendered);
      }
      $variables['header_sanitized'] = $header_sanitized;
    }

    // Rows, Footer - preserve row and cell attributes for table drag and
    // other Drupal behaviors (e.g. Layout Builder block order).
    foreach (['rows', 'footer'] as $section) {
      $rows = [];

      if (!empty($variables[$section])) {
        foreach ($variables[$section] as $row_key => $row) {
          $row_cells = $row['cells'] ?? $row['columns'] ?? [];
          $row_attrs = $row['attributes'] ?? [];
          $rows[$row_key] = [
            // Store as array so Element::children() does not receive an
            // object.
            'attributes' => $row_attrs instanceof Attribute ? $row_attrs->toArray() : (array) $row_attrs,
          ];
          foreach ($row_cells as $cell) {
            $cell_content = is_array($cell) ? ($cell['content'] ?? $cell['data'] ?? '') : $cell;
            $cell_attributes = is_array($cell) && isset($cell['attributes']) && $cell['attributes'] instanceof Attribute
              ? clone $cell['attributes']
              : new Attribute(is_array($cell) && isset($cell['attributes']) ? $cell['attributes'] : []);
            $cell_content = CivicthemeUtility::render($cell_content);
            $cell_content = HtmlContentProcessor::processHtmlContent($cell_content);
            $rows[$row_key]['cols'][] = [
              'content' => [
                '#type' => 'markup',
                '#markup' => $cell_content,
              ],
              // Store as array so Element::children() does not receive an
              // object.
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
  #[Hook('preprocess_views_view_table')]
  public function preprocessViewsViewTable(array &$variables): void {
    if (FeatureOptout::isOptedOut('views', CivicthemeConstants::OPTOUT_VIEWS_STYLE_TABLE, $variables['view'])) {
      $variables['civictheme_component_optout'] = TRUE;

      return;
    }

    $this->preprocessTable($variables);
  }

  /**
   * Add post-processing for all WYSIWYG fields.
   */
  protected function preprocessWysiwygField(array &$variables): void {
    if (isset($variables['field_name'], $variables['field_type']) && CivicthemeField::isWysiwygFieldType($variables['field_type'])) {
      foreach (Element::children($variables['items']) as $item) {
        if (!empty($variables['items'][$item]['content']['#text'])) {
          $theme = empty($variables['element']['#object']) ? NULL : CivicthemeField::getThemeValue($variables['element']['#object']);
          $variables['items'][$item]['content']['#text'] = HtmlContentProcessor::processHtmlContent($variables['items'][$item]['content']['#text'], [
            'theme' => $theme,
          ], $variables['items'][$item]['content']['#format'] ?? NULL);
        }
      }
    }
  }

  /**
   * Preprocess date range field.
   */
  protected function preprocessDateRangeField(array &$variables): void {
    if ($variables['field_name'] !== 'field_c_n_date_range') {
      return;
    }

    $variables['node'] = $variables['element']['#object'] ?? NULL;
    FieldMapper::nodeFieldDateRange($variables);
    unset($variables['node']);
  }

}
