<?php

/**
 * @file
 * Webform theme alterations.
 */

declare(strict_types=1);

use Drupal\Core\Template\Attribute;

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
function civictheme_preprocess_webform_html_editor_markup(array &$variables): void {
  $markup = (string) $variables['markup'];
  $processed = _civictheme_process__html_content($markup);
  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:basic-content',
    '#props' => [
      'vertical_spacing' => 'both',
      // Rendered fragments (element markup, descriptions, confirmation
      // messages) inherit their width from the surrounding layout: a nested
      // grid container would misalign them inside forms and messages.
      'is_contained' => FALSE,
    ],
    '#slots' => [
      'content' => [
        '#markup' => $processed,
      ],
    ],
  ];
}

/**
 * Implements template_preprocess_webform_element_more().
 */
function civictheme_preprocess_webform_element_more(array &$variables): void {
  // The theme propagation keys arrive inside the element attributes, which
  // webform has already converted to an Attribute object: extract the theme
  // and keep the keys from printing as attributes.
  $attributes = $variables['attributes'] ?? NULL;
  if (!$attributes instanceof Attribute) {
    return;
  }
  if (isset($attributes['#civictheme_theme'])) {
    $civictheme_theme = (string) $attributes['#civictheme_theme']->value();
    if (in_array($civictheme_theme, civictheme_theme_options(TRUE))) {
      $variables['theme'] = $civictheme_theme;
    }
  }
  $attributes->removeAttribute('#civictheme_theme', '#component_theme');
}

/**
 * Implements template_preprocess_webform_section().
 */
function civictheme_preprocess_webform_section(array &$variables): void {
  $variables['theme'] = _civictheme_form_element_get_component_theme($variables['element'] ?? []);
  unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
}

/**
 * Implements template_preprocess_webform_horizontal_rule().
 */
function civictheme_preprocess_webform_horizontal_rule(array &$variables): void {
  $variables['theme'] = _civictheme_form_element_get_component_theme($variables['element'] ?? []);
  unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
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
