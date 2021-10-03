<?php

/**
 * @file
 * Paragraph theme alterations.
 */

define('PARAGRAPH_TITLE', 'field_c_p_title');

define('PARAGRAPH_SUMMARY', 'field_c_p_summary');

define('PARAGRAPH_ICON', 'field_c_p_icon');

define('PARAGRAPH_THEME', 'field_c_p_theme');

/**
 * Generic setter of global variables for paragraphs as card renders.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card(&$variables) {
  _civic_preprocess_paragraph_summary($variables);
  _civic_preprocess_paragraph_title($variables);
  _civic_preprocess_paragraph_icon($variables);
  _civic_preprocess_paragraph_theme($variables);
}

/**
 * Global paragraph preprocessor calls.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_global(&$variables) {
  _civic_preprocess_paragraph_card($variables);

  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $type = $paragraph->getType();
  $preprocessor_function = '_civic_preprocess_paragraph_' . $type;
  if (function_exists($preprocessor_function)) {
    $preprocessor_function($variables);
  }
}

/**
 * Render Summary from Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField(PARAGRAPH_SUMMARY) && !$paragraph->get(PARAGRAPH_SUMMARY)->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get(PARAGRAPH_SUMMARY)->getString(), NULL, 100);
  }
}

/**
 * Render Title from Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField(PARAGRAPH_TITLE) && !$paragraph->get(PARAGRAPH_TITLE)->isEmpty()) {
    $variables['title'] = $paragraph->get(PARAGRAPH_TITLE)->getString();
  }
}

/**
 * Render Theme from Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField(PARAGRAPH_THEME) && !$paragraph->get(PARAGRAPH_THEME)->isEmpty()) {
    $variables['theme'] = $paragraph->get(PARAGRAPH_THEME)->getString();
  }
}

/**
 * Render Icon from Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField(PARAGRAPH_ICON) && !$paragraph->get(PARAGRAPH_ICON)->isEmpty()) {
    $media_target_id = $paragraph->get(PARAGRAPH_ICON)->getString();
    if ($image = _civic_media_image($media_target_id, 'field_c_m_icon')) {
      $variables['image'] = $image;
    }
  }
}
