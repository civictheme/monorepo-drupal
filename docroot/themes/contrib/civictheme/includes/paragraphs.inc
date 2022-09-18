<?php

/**
 * @file
 * Paragraph theme alterations.
 */

use Drupal\Component\Utility\Html;

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
    return;
  }
  // @todo documentation for the field api ie they must have certain fields in
  // card fielded paragraph types.
  _civictheme_preprocess_paragraph__card__icon($variables);
  _civictheme_preprocess_paragraph__card__image($variables);

}

/**
 * Global paragraph preprocessor calls.
 */
function _civictheme_preprocess_paragraph(&$variables) {
  // Set theme for all paragraphs.
  $variables['theme'] = civictheme_get_field_theme_value($variables['paragraph']);

  // Set content for all paragraphs.
  if (!empty($variables['content']['field_c_p_content'])) {
    $variables['content'] = \Drupal::service('renderer')->render($variables['content']['field_c_p_content']);
  }

  // Generic Drupal classes from paragraph template files.
  _civictheme_preprocess_paragraph_classes($variables);

  // Global paragraph fields.
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__space($variables);
  _civictheme_preprocess_paragraph__field__background($variables);
  _civictheme_preprocess_paragraph__civictheme_card($variables);
  _civictheme_preprocess_paragraph__field__webform($variables);
}

/**
 * Pre-process drupal generic paragraph classes.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_paragraph_classes(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['attributes']['class'][] = 'paragraph';
  $variables['attributes']['class'][] = Html::cleanCssIdentifier('paragraph--type--' . $paragraph->bundle());
  $variables['attributes']['class'][] = Html::cleanCssIdentifier('paragraph--view-mode--' . $variables['view_mode']);
  $variables['attributes']['class'][] = !$paragraph->isPublished() ? 'paragraph--unpublished' : '';
}

/**
 * Pre-process summary for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__summary(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_summary')) {
    $variables['summary'] = text_summary(civictheme_get_field_value($paragraph, 'field_c_p_summary'), NULL, 100);
  }
}

/**
 * Pre-process title for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__title(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_title')) {
    $variables['title'] = civictheme_get_field_value($paragraph, 'field_c_p_title');
  }
}

/**
 * Pre-process "With space" for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__space(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_space')) {
    $variables['vertical_space'] = civictheme_get_field_value($paragraph, 'field_c_p_space');
  }
}

/**
 * Pre-process "with background" for CivicTheme paragraphs.
 */
function _civictheme_preprocess_paragraph__field__background(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  if (civictheme_get_field_value($paragraph, 'field_c_p_background')) {
    $component_name = str_replace('_', '-', $paragraph->getType());
    // Components are namespaced `civictheme_<COMPONENT_NAME>`
    // Front-end are namespaced `ct-attachment--with-background` so need to
    // convert to front-end naming guide.
    $component_name = str_replace('civictheme', 'ct', $component_name);
    if (!array_key_exists('modifier_class', $variables)) {
      $variables['modifier_class'] = '';
    }
    $variables['modifier_class'] .= ' ' . $component_name . '--with-background';
  }
}

/**
 * Pre-process icon for CivicTheme cards.
 */
function _civictheme_preprocess_paragraph__card__icon(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $media = civictheme_get_field_value($paragraph, 'field_c_p_icon', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_process_variables($media);
  }
}

/**
 * Pre-process Icon for CivicTheme Paragraphs.
 */
function _civictheme_preprocess_paragraph__card__image(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $media = civictheme_get_field_value($paragraph, 'field_c_p_image', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_process_variables($media);
  }
}

/**
 * Gets CivicTheme card types.
 */
function _civictheme_get_card_types() {
  // @todo extend to allow for child theme - implement as theme setting.
  return [
    'civictheme_card_container',
    'civictheme_event_card_ref',
    'civictheme_event_card',
    'civictheme_navigation_card_ref',
    'civictheme_navigation_card',
    'civictheme_promo_card',
    'civictheme_promo_card_ref',
    'civictheme_publication_card',
    'civictheme_subject_card_ref',
    'civictheme_subject_card',
    'civictheme_service_card',
    'civictheme_task_card',
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
 * Preprocessing reference webfrom paragraphs.
 */
function _civictheme_preprocess_paragraph__field__webform(&$variables) {
  $paragraph = $variables['paragraph'];
  if (civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $variables['reference_webform'] = $paragraph->get('field_c_p_webform')->view();
  }
}
