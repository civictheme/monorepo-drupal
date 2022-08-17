<?php

/**
 * @file
 * Field-related functions.
 */

use Drupal\Core\Render\Element;

/**
 * Add post-processing for all WYSIWYG fields.
 */
function _civictheme_preprocess_wysiwyg_field(&$variables) {
  if (isset($variables['field_name'])) {
    if (civictheme_field_is_wysiwyg($variables['field_name'])) {
      foreach (Element::children($variables['items']) as $item) {
        if (!empty($variables['items'][$item]['content']['#text'])) {
          $variables['items'][$item]['content']['#text'] = civictheme_process_link_attributes($variables['items'][$item]['content']['#text']);
        }
      }
    }
  }
}
