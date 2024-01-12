<?php

/**
 * @file
 * WYSIWYG preprocessing.
 */

declare(strict_types=1);

use Drupal\Core\Render\Element;

/**
 * Add post-processing for all WYSIWYG fields.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_wysiwyg_field(array &$variables): void {
  if (isset($variables['field_name']) && _civictheme_field_is_wysiwyg($variables['field_name'])) {
    foreach (Element::children($variables['items']) as $item) {
      if (!empty($variables['items'][$item]['content']['#text'])) {
        $theme = empty($variables['element']['#object']) ? NULL : civictheme_get_field_theme_value($variables['element']['#object']);
        $variables['items'][$item]['content']['#text'] = _civictheme_process__html_content($variables['items'][$item]['content']['#text'], [
          'theme' => $theme,
        ]);
      }
    }
  }
}
