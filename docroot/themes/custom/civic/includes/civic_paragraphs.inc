<?php

/**
 * @file
 * Paragraph theme alterations.
 */

require_once 'paragraph--civic_attachment.inc';
require_once 'paragraph--civic_callout.inc';
require_once 'paragraph--civic_card_container.inc';
require_once 'paragraph--civic_card_event.inc';
require_once 'paragraph--civic_card_navigation.inc';
require_once 'paragraph--civic_card_promo.inc';
require_once 'paragraph--civic_card_subject.inc';
require_once 'paragraph--civic_card_task.inc';
require_once 'paragraph--civic_quick_links.inc';
require_once 'paragraph--civic_service_card.inc';
require_once 'paragraph--civic_content.inc';
require_once 'paragraph--civic_search.inc';
require_once 'paragraph--civic_social_icon.inc';

require_once 'paragraph--civic_accordion.inc';

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civic_theme_suggestions_paragraph_alter(array &$suggestions, array $variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  $type = $paragraph->getType();

  // Add a generic card theme suggestion for reference cards since the component
  // is being handled via node preprocessing functions.
  if (in_array($type, _civic_get_card_types()) && $paragraph->hasField('field_c_p_reference')) {
    $suggestions[] = 'paragraph__civic_reference_card';
  }
}

/**
 * Generic setter of global variables for paragraphs as card renders.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $bundle = $paragraph->getType();
  if (!in_array($bundle, _civic_get_card_types())) {
    return;
  }
  // Cards that are generated via reference are handled separately.
  if ($paragraph->hasField('field_c_p_reference')) {
    _civic_preprocess_paragraph_reference_card($variables);
  }
  else {
    // @todo documentation for the field api ie they must have certain fields in
    // card fielded paragraph types.
    _civic_preprocess_paragraph_card__icon($variables);
    _civic_preprocess_paragraph_card__image($variables);
  }

}

/**
 * Global paragraph preprocessor calls.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph(&$variables) {
  // Global paragraph fields.
  _civic_preprocess_paragraph_theme($variables);
  _civic_preprocess_paragraph_title($variables);
  _civic_preprocess_paragraph_summary($variables);

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
 * Preprocess summary for Civic paragraphs.
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
 * Preprocess title for Civic paragraphs.
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
 * Preprocess Theme fields for Civic paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($theme = _civic_paragraph_get_theme($paragraph)) {
    $variables['theme'] = $theme;
  }
}

/**
 * Preprocess icon for Civic cards.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    $media = $paragraph->get('field_c_p_icon')->first()->get('entity')->getTarget()->getValue();
    if ($image = _civic_media_image($media)) {
      $variables['image'] = $image;
    }
  }
}

/**
 * Preprocess Icon for Civic Paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_card__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_image') && !$paragraph->get('field_c_p_image')->isEmpty()) {
    $media = $paragraph->get('field_c_p_image')->first()->get('entity')->getTarget()->getValue();
    $variables['image'] = _civic_media_image($media);
  }
}

/**
 * Gets Civic card types.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civic_card_container',
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

/**
 * Preprocessing reference cards paragraphs.
 *
 * @SuppressWarnings(PHPMD)
 */
function _civic_preprocess_paragraph_reference_card(&$variables) {
  // Card reference paragraph types are handled on a generic template.
  // Card type are handled at node level via view modes.
  // @see civic_theme_suggestions_paragraph_alter()
  $variables['reference_card'] = $variables['content']['field_c_p_reference'];
}
