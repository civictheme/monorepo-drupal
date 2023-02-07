<?php

/**
 * @file
 * CivicTheme Content paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_content(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'content',
    'theme',
    'vertical_spacing',
    'background',
  ]);
}
