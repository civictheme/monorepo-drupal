<?php

/**
 * @file
 * Form alterations.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\Core\Template\AttributeArray;

/**
 * Implements hook_form_alter().
 */
function civic_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  // Adjusting exposed view forms to hide submit button.
  if ($form['#form_id'] === 'views_exposed_form') {
    $form['actions']['submit']['#attributes']['class'][] = 'hide-xs';
    $form['#attached']['library'][] = 'civic/civic.ajax_views';
    foreach ($form as $key => &$element) {

      if (strpos($key, '#') === 0 || empty($element['#type'])) {
        continue;
      }

      _civic_form_alter__convert_exposed_form_elements($element, $form);

    }
  }
}

/**
 * Convert form elements to correct format.
 */
function _civic_form_alter__convert_exposed_form_elements(&$element, $form) {
  if ($element['#type'] === 'select') {
    if (!empty($element['#multiple'])) {
      $element['#type'] = 'checkboxes';
      unset($element['#multiple']);
    }
    else {
      $element['#type'] = 'radios';
    }

    $element['#civic-large-filter'] = $form['#id'];
  }
}

/**
 * Implements template_preprocess_input().
 */
function civic_preprocess_input(&$variables) {
  $variables['state'] = 'none';
  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);

    if (in_array('error', $variables['attributes']['class'])) {
      $variables['state'] = 'error';
    }

    unset($variables['attributes']['class']);
  }
}

/**
 * Implements template_preprocess_select().
 */
function civic_preprocess_select(&$variables) {
  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
}

/**
 * Implements template_preprocess_form_element().
 */
function civic_preprocess_form_element(&$variables) {
  // Handle any malformed attribute class properties.
  $variables['attributes']['class'] = $variables['attributes']['class'] ?? [];
  $variables['attributes']['class'] = is_string($variables['attributes']['class']) ? [$variables['attributes']['class']] : $variables['attributes']['class'];

  // Add missing core Drupal form element classes that are added in template
  // file.
  $variables['attributes']['class'][] = 'form-item';
  $variables['attributes']['class'][] = 'js-form-item';

  if (!empty($variables['type'])) {
    $variables['attributes']['class'][] = 'form-type-' . Html::getClass($variables['type']);
    $variables['attributes']['class'][] = 'js-form-type-' . Html::getClass($variables['type']);
  }

  if (!empty($variables['name'])) {
    $variables['attributes']['class'][] = 'form-item-' . Html::getClass($variables['name']);
    $variables['attributes']['class'][] = 'js-form-item-' . Html::getClass($variables['name']);
  }

  if (!empty($variables['errors'])) {
    $variables['attributes']['class'][] = 'form-item--error';
  }

  if (isset($variables['description'])) {
    if (empty($variables['description']['attributes'])) {
      $variables['description']['attributes'] = new Attribute();
    }
    $variables['description']['attributes']->addClass('description');
  }

  if (isset($variables['attributes']['class']) && is_array($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }

  if (isset($variables['description']['attributes']['class']) && $variables['description']['attributes']['class'] instanceof AttributeArray) {
    $variables['description_modifier_class'] = implode(' ', $variables['description']['attributes']['class']->value());
  }
}

/**
 * Implements template_preprocess_form_element_label().
 */
function civic_preprocess_form_element_label(&$variables) {
  // Adding in core template classes.
  if (!empty($variables['required'])) {
    $variables['attributes']['class'][] = 'js-form-required';
    $variables['attributes']['class'][] = 'form-required';
  }
  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
}

/**
 * Implements template_preprocess_fieldset().
 */
function civic_preprocess_fieldset(&$variables) {
  // Add core drupal form classes.
  $variables['attributes']['class'] = $variables['attributes']['class'] ?? [];
  $variables['attributes']['class'] = array_merge($variables['attributes']['class'], [
    'js-form-item',
    'form-item',
    'js-form-wrapper',
    'form-wrapper',
  ]);
  $children = Element::children($variables['element']);
  // Fieldset's for radio checkbox form groups are have different typography
  // compared to field section fieldsets.
  foreach ($children as $child) {
    if (!isset($variables['element'][$child]['#type'])) {
      continue;
    }
    $is_radio_or_checkbox = in_array(
      $variables['element'][$child]['#type'],
      ['radio', 'checkbox']
    );
    if ($is_radio_or_checkbox) {
      break;
    }
  }
  $type = $is_radio_or_checkbox ? 'fieldset-radio' : 'fieldset';
  $variables['type'] = $variables['type'] ?? $type;

  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
  if (isset($variables['legend_span']['attributes'])) {
    $variables['legend_span_attributes'] = $variables['legend_span']['attributes'];
  }
  if (isset($variables['legend']['title'])) {
    $variables['legend'] = $variables['legend']['title'];
  }
  if (isset($variables['legend']['attributes']['class'])) {
    $variables['legend_modifier_class'] = implode(' ', $variables['legend']['attributes']['class']);
    unset($variables['legend']['attributes']['class']);
    $variables['legend_attributes'] = $variables['legend']['attributes'] ?? '';
  }

  if (isset($variables['description']['content'])) {
    $variables['description_attributes'] = $variables['description']['attributes'] ?? '';
    if (isset($variables['description']['attributes']['class'])) {
      $variables['description_modifier_class'] = implode(' ', $variables['description']['attributes']['class']);
    }
    $variables['description'] = $variables['description']['content'];
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civic_theme_suggestions_checkboxes_alter(array &$suggestions, array $variables) {
  if (!empty($variables['element']['#civic-large-filter'])) {
    switch ($variables['element']['#type']) {
      case 'checkboxes':
        $suggestions[] = 'checkboxes__civic_dropdown_filter';
        break;
      case 'radios':
      $suggestions[] = 'radios__civic_dropdown_filter';
    }
  }
}

/**
 * Implements template_preprocess_checkboxes().
 */
function civic_preprocess_checkboxes(&$variables) {
  $element = &$variables['element'];
  if (isset($element['#civic-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['filter_group'] = $element['#civic-large-filter'];
  }
  else {
    // Standard theme wrapper for checkboxes was removed to allow for
    // civic large filter checkboxes and so we need to add it back as a
    // preprocess function as we cannot add a pre_render function at theme
    // level.
    $variables['required'] = $element['#required'] ?? FALSE;
    $variables['type'] = 'fieldset-radio';
    $variables['fieldset_legend'] = $element['#title'];
    $variables['modifier_class'] = !empty($variables['element']['#attributes']['class']) ? implode(' ', $variables['element']['#attributes']['class']) : '';
    $variables['fieldset_attributes'] = $variables['element']['attributes'] ?? '';
  }
}

/**
 * Implements hook_element_info_alter().
 */
function civic_element_info_alter(array &$types) {
  if (isset($types['checkboxes'])) {
    // Checkboxes adds a wrapping fieldset to the checkboxes element
    // which we do not want when rendering dropdown filters.
    // The fieldset wrapper is added back manually for all other fieldsets.
    // @see civic_preprocess_checkboxes
    unset($types['checkboxes']['#pre_render']);
  }
}
