<?php

/**
 * @file
 * Webform theme alterations.
 */

declare(strict_types=1);

/**
 * Pre-process for Webform paragraph.
 */
function civictheme_preprocess_paragraph__civictheme_webform(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  $paragraph = $variables['paragraph'] ?? NULL;
  if ($paragraph && civictheme_field_has_value($paragraph, 'field_c_p_webform')) {
    $variables['referenced_webform'] = $paragraph->get('field_c_p_webform')->view();
  }
}

/**
 * Implements hook_preprocess_webform_html_editor_markup().
 */
function civictheme_preprocess_webform_html_editor_markup(array &$variables) {
  $markup = (string) $variables['markup'];
  $processed = _civictheme_process__html_content($markup);
  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:basic-content',
    '#props' => [
      'vertical_spacing' => 'both',
      'is_contained' => TRUE,
    ],
    '#slots' => [
      'content' => [
        '#markup' => $processed,
      ],
    ],
  ];
}

/**
 * Implements hook_preprocess_container__webform_container().
 */
function civictheme_preprocess_container__webform_container(array &$variables): void {
  if (!civictheme_is_webform_element($variables['element'])) {
    return;
  }
  _civictheme_preprocess_form_element__webform__overrides($variables);
  $variables['content'] = $variables['children'];
  unset($variables['children']);
}
