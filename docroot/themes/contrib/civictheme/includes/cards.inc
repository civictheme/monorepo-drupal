<?php

/**
 * @file
 * Card theme alterations.
 *
 * The per-field processing functions were extracted into helpers in order to
 * preserve data consistency when preprocessing the same fields within different
 * cards.
 * This also avoids "hidden" processing and helps to navigate to the helper
 * within IDE.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__link($variables);
  _civictheme_preprocess_paragraph__field__topics($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);

  $variables['subtitle'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_subtitle', TRUE);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_event_card(&$variables) {
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__date_range($variables);
  _civictheme_preprocess_paragraph__field__link($variables);
  _civictheme_preprocess_paragraph__field__topics($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);

  $variables['location'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_location', TRUE);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card(&$variables) {
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__link($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);

  $variables['image_as_icon'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_show_image_as_icon') ?? FALSE;
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_publication_card(&$variables) {
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__summary($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);

  $document = civictheme_get_field_value($variables['paragraph'], 'field_c_p_document', TRUE) ?? NULL;
  if ($document) {
    $vars = civictheme_media_get_variables($document);
    if ($vars) {
      $variables['file'] = $vars;
    }
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_subject_card(&$variables) {
  _civictheme_preprocess_paragraph__field__image($variables);
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__link($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_service_card(&$variables) {
  _civictheme_preprocess_paragraph__field__title($variables);
  _civictheme_preprocess_paragraph__field__links($variables);
  _civictheme_preprocess_paragraph__field__theme($variables);
}
