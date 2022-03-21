<?php

/**
 * @file
 * Filter-related functions.
 */

/**
 * Theme suggestions for all civic filter elements.
 */
function _civic_theme_suggestions__civic_filter(array &$suggestions, array $variables, $type = NULL) {
  if (empty($variables['element']['#type'])) {
    return;
  }

  $type = $type ?? $variables['element']['#type'];

  if (!empty($variables['element']['#civic-large-filter'])) {
    $suggestions[] = $type . '__civic_dropdown_filter';
  }
  elseif (!empty($variables['element']['#civic-basic-filter'])) {
    $suggestions[] = $type . '__civic_basic_filter';
  }
}

/**
 * Theme suggestions for civic basic filter elements.
 */
function _civic_theme_suggestions__civic_basic_filter(array &$suggestions, array $variables, $type = NULL) {
  // For civic basic filter both radios / checkboxes and checkbox / radio
  // require custom templates.
  if (in_array($variables['element']['#type'], ['radio', 'checkbox'])
    && isset($variables['element']['#attributes']['#civic-basic-filter'])) {
    $suggestions[] = 'form_element__civic_filter_chip';
  }
}
