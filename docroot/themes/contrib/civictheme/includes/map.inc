<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_map(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'embed_url',
    'address',
    'view_link',
    'theme',
    'vertical_spacing',
    'background',
  ]);
}
