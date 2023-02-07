<?php

/**
 * @file
 * CivicTheme Attachment paragraph component.
 */

use Drupal\file\Entity\File;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
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
