<?php

/**
 * @file
 * Filter-related functions.
 */

/**
 * Theme suggestions for all civictheme filter elements.
 */
function _civictheme_theme_suggestions__civictheme_filter(array &$suggestions, array $variables, $type = NULL) {
  if (empty($variables['element']['#type'])) {
    return;
  }

  $type = $type ?? $variables['element']['#type'];

  if (!empty($variables['element']['#civictheme-large-filter'])) {
    $suggestions[] = $type . '__civictheme_dropdown_filter';
  }
  elseif (!empty($variables['element']['#civictheme-basic-filter'])) {
    $suggestions[] = $type . '__civictheme_basic_filter';
  }
}

/**
 * Theme suggestions for civictheme basic filter elements.
 */
function _civictheme_theme_suggestions__civictheme_basic_filter(array &$suggestions, array $variables, $type = NULL) {
  // For civictheme basic filter both radios / checkboxes and checkbox / radio
  // require custom templates.
  if (in_array($variables['element']['#type'], ['radio', 'checkbox'])
    && isset($variables['element']['#attributes']['#civictheme-basic-filter'])) {
    $suggestions[] = 'form_element__civictheme_filter_chip';
  }
}
