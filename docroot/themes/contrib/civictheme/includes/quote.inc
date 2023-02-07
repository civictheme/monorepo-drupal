<?php

/**
 * @file
 * CivicTheme Quote paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_quote(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'content',
    'author',
    'theme',
    'vertical_spacing',
  ]);
}
