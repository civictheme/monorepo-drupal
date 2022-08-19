<?php

/**
 * @file
 * Field-related functions.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;

/**
 * Add post-processing for all WYSIWYG fields.
 */
function _civictheme_preprocess_wysiwyg_field(&$variables) {
  if (isset($variables['field_name'])) {
    if (civictheme_field_is_wysiwyg($variables['field_name'])) {
      foreach (Element::children($variables['items']) as $item) {
        if (!empty($variables['items'][$item]['content']['#text'])) {
          $variables['items'][$item]['content']['#text'] = _civictheme_process_link_attributes($variables['items'][$item]['content']['#text']);
        }
      }
    }
  }
}

/**
 * Adds CSS class to all links.
 *
 * @param string $html
 *   The string to be processed.
 *
 * @return string
 *   The processed string.
 */
function _civictheme_process_link_attributes(string $html) {
  $dom = Html::load($html);
  if ($dom) {
    $anchors = $dom->getElementsByTagName('a');
    if ($anchors) {
      $host = \Drupal::service('request_stack')->getCurrentRequest()->getHost();
      $overridden_domains = civictheme_get_override_domains();
      foreach ($anchors as $a) {
        if ($a->hasAttribute('href')) {
          $classes = $a->getAttribute('class') ? $a->getAttribute('class') . ' civictheme-link' : 'civictheme-link';
          $url = $a->getAttribute('href');
          if (civictheme_link_is_external($url, $host, $overridden_domains)) {
            $classes .= ' civictheme-link--external';
          }
          $a->setAttribute('class', $classes);
        }
      }
      $html = Html::serialize($dom);
    }
  }
  return $html;
}
