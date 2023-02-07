<?php

/**
 * @file
 * CivicTheme Campaign paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_campaign(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'image_position',
    'topics',
    'title',
    'date',
    'content',
    'links',
    'theme',
    'vertical_spacing',
  ]);
}
