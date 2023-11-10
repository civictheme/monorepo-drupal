<?php

/**
 * @file
 * WYSIWYG preprocessing.
 */

use Drupal\Core\Render\Element;

/**
 * Add post-processing for all WYSIWYG fields.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_wysiwyg_field(array &$variables): void {
  if (isset($variables['field_name'])) {
    if (_civictheme_field_is_wysiwyg($variables['field_name'])) {
      foreach (Element::children($variables['items']) as $item) {
        if (!empty($variables['items'][$item]['content']['#text'])) {
          $theme = !empty($variables['element']['#object']) ? civictheme_get_field_theme_value($variables['element']['#object']) : NULL;
          $variables['items'][$item]['content']['#text'] = _civictheme_process__html_content($variables['items'][$item]['content']['#text'], [
            'theme' => $theme,
          ]);
        }
      }
    }
  }
}
