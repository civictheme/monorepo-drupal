<?php

/**
 * @file
 * Paragraph theme alterations.
 */

use Drupal\paragraphs\Entity\Paragraph;

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
 */
function _civic_preprocess_paragraph__civic_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $bundle = $paragraph->getType();
  if (!in_array($bundle, _civic_get_card_types())) {
    return;
  }
  // Cards that are generated via reference are handled separately.
  if ($paragraph->hasField('field_c_p_reference')) {
    _civic_preprocess_paragraph__reference_card($variables);
  }
  else {
    // @todo documentation for the field api ie they must have certain fields in
    // card fielded paragraph types.
    _civic_preprocess_paragraph__card__icon($variables);
    _civic_preprocess_paragraph__card__image($variables);
  }

}

/**
 * Global paragraph preprocessor calls.
 */
function _civic_preprocess_paragraph(&$variables) {
  // Global paragraph fields.
  _civic_preprocess_paragraph__field__theme($variables);
  _civic_preprocess_paragraph__field__title($variables);
  _civic_preprocess_paragraph__field__summary($variables);
  _civic_preprocess_paragraph__field__space($variables);
  _civic_preprocess_paragraph__field__background($variables);
  _civic_preprocess_paragraph__civic_card($variables);

}

/**
 * Pre-process summary for Civic paragraphs.
 */
function _civic_preprocess_paragraph__field__summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_summary') && !$paragraph->get('field_c_p_summary')->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get('field_c_p_summary')->getString(), NULL, 100);
  }
}

/**
 * Pre-process title for Civic paragraphs.
 */
function _civic_preprocess_paragraph__field__title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_title') && !$paragraph->get('field_c_p_title')->isEmpty()) {
    $variables['title'] = $paragraph->get('field_c_p_title')->getString();
  }
}

/**
 * Pre-process "with space" for Civic paragraphs.
 */
function _civic_preprocess_paragraph__field__space(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_space') && !$paragraph->get('field_c_p_space')->isEmpty()) {
    $component_name = str_replace('_', '-', $paragraph->getType());
    $variables['modifier_class'] = $component_name . '--with-space-' . $paragraph->get('field_c_p_space')->getString();
  }
}

/**
 * Pre-process "with background" for Civic paragraphs.
 */
function _civic_preprocess_paragraph__field__background(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_background')
    && !$paragraph->get('field_c_p_background')->isEmpty()
    && $paragraph->field_c_p_background->value != FALSE
  ) {
    $component_name = str_replace('_', '-', $paragraph->getType());
    if (!array_key_exists('modifier_class', $variables)) {
      $variables['modifier_class'] = '';
    }
    $variables['modifier_class'] .= ' ' . $component_name . '--with-background';
  }
}

/**
 * Pre-process Theme fields for Civic paragraphs.
 */
function _civic_preprocess_paragraph__field__theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($theme = _civic_paragraph_get_theme($paragraph)) {
    $variables['theme'] = $theme;
  }
}

/**
 * Pre-process icon for Civic cards.
 */
function _civic_preprocess_paragraph__card__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    $target = $paragraph->get('field_c_p_icon')->first()->get('entity')->getTarget();
    if ($target) {
      $media = $target->getValue();
      if ($image = civic_media_image_process_variables($media)) {
        $variables['image'] = $image;
      }
    }
  }
}

/**
 * Pre-process Icon for Civic Paragraphs.
 */
function _civic_preprocess_paragraph__card__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_image') && !$paragraph->get('field_c_p_image')->isEmpty()) {
    $target = $paragraph->get('field_c_p_image')->first()->get('entity')->getTarget();
    if ($target) {
      $media = $target->getValue();
      $variables['image'] = civic_media_image_process_variables($media);
    }
  }
}

/**
 * Gets Civic card types.
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
    'civic_publication_card',
  ];
}

/**
 * Preprocessing reference cards paragraphs.
 */
function _civic_preprocess_paragraph__reference_card(&$variables) {
  // Card reference paragraph types are handled on a generic template.
  // Card type are handled at node level via view modes.
  // @see civic_theme_suggestions_paragraph_alter()
  $variables['reference_card'] = $variables['content']['field_c_p_reference'];
}

/**
 * Get theme name from Paragraph Entity.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   Paragraph Entity.
 */
function _civic_paragraph_get_theme(Paragraph $paragraph) {
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    return $paragraph->get('field_c_p_theme')->getString();
  }
  return FALSE;
}
