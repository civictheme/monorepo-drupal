<?php

/**
 * @file
 * Card theme alterations.
 */

function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'title',
    'summary',
    'link',
    'topics',
    'theme',
  ]);

  $variables['subtitle'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_subtitle', TRUE);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'title',
    'summary',
    'link',
    'theme',
  ]);

  $variables['image_as_icon'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_show_image_as_icon') ?? FALSE;
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_publication_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'title',
    'summary',
    'document',
    'theme',
  ]);
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_subject_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'image',
    'title',
    'link',
    'theme',
  ]);
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_service_card(&$variables) {
  _civictheme_preprocess_paragraph__fields($variables, [
    'title',
    'links',
    'theme',
  ]);
}
