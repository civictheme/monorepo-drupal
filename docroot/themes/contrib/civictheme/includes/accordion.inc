<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_accordion(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'panels',
    'expand_all',
    'theme',
    'vertical_spacing',
    'background',
  ]);
}
