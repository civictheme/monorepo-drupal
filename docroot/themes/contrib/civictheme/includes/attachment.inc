<?php

/**
 * @file
 * CivicTheme Attachment paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_attachment(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'title',
    'content',
    'attachments',
    'theme',
    'vertical_spacing',
    'background',
  ]);
}
