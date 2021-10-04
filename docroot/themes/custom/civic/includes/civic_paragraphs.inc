<?php

/**
 * @file
 * Paragraph theme alterations.
 */

/**
 * Generic setter of global variables for paragraphs as card renders.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card(&$variables) {
  // Is this paragraph a card?
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $type = $paragraph->getType();
  if (!in_array($type, _civic_get_card_types())) {
    return;
  }
  // Cards that are generated via reference are handled separately.
  if ($paragraph->hasField('field_c_p_reference')) {
    _civic_preprocess_paragraph_reference_card();
  }
  else {
    //@todo documentation for the field api ie they must have certain fields in
    // card fielded paragraph types.
    _civic_preprocess_paragraph_title($variables);
    _civic_preprocess_paragraph_summary($variables);
    _civic_preprocess_paragraph_icon($variables);
  }

}

/**
 * Global paragraph preprocessor calls.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_global(&$variables) {
  // Global paragraph fields.
  _civic_preprocess_paragraph_theme($variables);

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
  if ($paragraph->hasField('field_c_p_summary') && !$paragraph->get('field_c_p_summary')->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get('field_c_p_summary')->getString(), NULL, 100);
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
  if ($paragraph->hasField('field_c_p_title') && !$paragraph->get('field_c_p_title')->isEmpty()) {
    $variables['title'] = $paragraph->get('field_c_p_title')->getString();
  }
}

/**
 * Render Theme fields from Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
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
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    $media_target_id = $paragraph->get('field_c_p_icon')->getString();
    if ($image = _civic_media_image($media_target_id, 'field_c_m_icon')) {
      $variables['image'] = $image;
    }
  }
}

/**
 * Civic card types.
 */
function _civic_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civic_card_task',
    'civic_card_event_reference',
    'civic_card_event',
    'civic_card_navigation_reference',
    'civic_card_navigation',
    'civic_card_promo',
    'civic_card_reference_promo',
    'civic_card_subject_reference',
    'civic_card_subject',
    'civic_service_card',
  ];
}

function _civic_preprocess_paragraph_reference_card(&$variables) {
  $x = 1;
}
