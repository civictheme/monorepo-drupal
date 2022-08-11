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
  $wysiwyg_field_list = [
    'body',
    'field_c_m_transcript',
    'field_c_n_body',
    'field_c_p_content',
  ];
  if (isset($variables['field_name'])) {
    if (in_array($variables['field_name'], $wysiwyg_field_list)) {
      foreach (Element::children($variables['items']) as $item) {
        if (!empty($variables['items'][$item]['content']['#text'])) {
          $variables['items'][$item]['#markup'] = civictheme_process_link_attributes($variables['items'][$item]['content']['#text']);
        }
      }
    }
  }
}
