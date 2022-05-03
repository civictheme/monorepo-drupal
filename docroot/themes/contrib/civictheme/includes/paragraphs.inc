<?php

/**
 * @file
 * Paragraph theme alterations.
 */

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civictheme_theme_suggestions_paragraph_alter(array &$suggestions, array $variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['elements']['#paragraph'];
  $type = $paragraph->getType();

  // Add a generic card theme suggestion for reference cards since the component
  // is being handled via node preprocessing functions.
  if (in_array($type, _civictheme_get_card_types()) && $paragraph->hasField('field_c_p_reference')) {
    $suggestions[] = 'paragraph__civictheme_reference_card';
  }
}

/**
 * Generic setter of global variables for paragraphs as card renders.
 */
function _civictheme_preprocess_paragraph__civictheme_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $bundle = $paragraph->getType();
  if (!in_array($bundle, _civictheme_get_card_types())) {
    return;
  }
  // Cards that are generated via reference are handled separately.
  if ($paragraph->hasField('field_c_p_reference')) {
    _civictheme_preprocess_paragraph__reference_card($variables);
  }
  else {
    // @todo documentation for the field api ie they must have certain fields in
    // card fielded paragraph types.
    _civictheme_preprocess_paragraph__card__icon($variables);
    _civictheme_preprocess_paragraph__card__image($variables);
  }

}

/**
 * Global paragraph preprocessor calls.
 */
function _civictheme_preprocess_paragraph(&$variables) {
  // Global paragraph fields.
  _civictheme_preprocess_paragraph__field__theme($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__space($variables);
  _civictheme_preprocess_paragraph__field__background($variables);
  _civictheme_preprocess_paragraph__civictheme_card($variables);

}

/**
 * Pre-process summary for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_summary') && !$paragraph->get('field_c_p_summary')->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get('field_c_p_summary')->getString(), NULL, 100);
  }
}

/**
 * Pre-process title for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_title') && !$paragraph->get('field_c_p_title')->isEmpty()) {
    $variables['title'] = $paragraph->get('field_c_p_title')->getString();
  }
}

/**
 * Pre-process "With space" for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__space(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_space') && !$paragraph->get('field_c_p_space')->isEmpty()) {
    $variables['vertical_space'] = $paragraph->get('field_c_p_space')->getString();
  }
}

/**
 * Pre-process "with background" for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__background(&$variables) {
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
 * Pre-process Theme fields for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__theme(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($theme = _civictheme_paragraph_get_theme($paragraph)) {
    $variables['theme'] = $theme;
  }
}

/**
 * Pre-process icon for CivicTheme cards.
 */
function _civictheme_preprocess_paragraph__card__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    $target = $paragraph->get('field_c_p_icon')->first()->get('entity')->getTarget();
    if ($target) {
      $media = $target->getValue();
      if ($image = civictheme_media_image_process_variables($media)) {
        $variables['image'] = $image;
      }
    }
  }
}

/**
 * Pre-process Icon for CivicTheme Paragraphs.
 */
function _civictheme_preprocess_paragraph__card__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if ($paragraph->hasField('field_c_p_image') && !$paragraph->get('field_c_p_image')->isEmpty()) {
    $target = $paragraph->get('field_c_p_image')->first()->get('entity')->getTarget();
    if ($target) {
      $media = $target->getValue();
      $variables['image'] = civictheme_media_image_process_variables($media);
    }
  }
}

/**
 * Gets CivicTheme card types.
 */
function _civictheme_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civictheme_card_container',
    'civictheme_card_task',
    'civictheme_card_event_ref',
    'civictheme_card_event',
    'civictheme_card_navigation_ref',
    'civictheme_card_navigation',
    'civictheme_card_promo',
    'civictheme_card_reference_promo',
    'civictheme_card_subject_ref',
    'civictheme_card_subject',
    'civictheme_service_card',
    'civictheme_publication_card',
  ];
}

/**
 * Preprocessing reference cards paragraphs.
 */
function _civictheme_preprocess_paragraph__reference_card(&$variables) {
  // Card reference paragraph types are handled on a generic template.
  // Card type are handled at node level via view modes.
  // @see civictheme_theme_suggestions_paragraph_alter()
  $variables['reference_card'] = $variables['content']['field_c_p_reference'];
}

/**
 * Get theme name from Paragraph Entity.
 *
 * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
 *   Paragraph Entity.
 */
function _civictheme_paragraph_get_theme(Paragraph $paragraph) {
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    return $paragraph->get('field_c_p_theme')->getString();
  }
  return FALSE;
}
