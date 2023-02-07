<?php

/**
 * @file
 * CivicTheme iFrame paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_iframe(&$variables) {
    _civictheme_preprocess_paragraph__fields($variables, [
    'url',
    'width',
    'height',
    'component_attributes',
    'theme',
    'vertical_spacing',
    'background',
  ]);
}
