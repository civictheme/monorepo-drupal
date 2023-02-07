<?php

/**
 * @file
 * CivicTheme Next step paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_next_step(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'title',
    'content',
    'link',
    'theme',
    'vertical_spacing',
  ]);
}
