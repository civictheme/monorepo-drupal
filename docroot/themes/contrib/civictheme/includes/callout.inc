<?php

/**
 * @file
 * CivicTheme Callout paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_callout(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'title',
    'content',
    'links',
    'theme',
    'vertical_spacing',
  ]);
}
