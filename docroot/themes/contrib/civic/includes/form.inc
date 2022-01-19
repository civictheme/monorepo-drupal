<?php

/**
 * @file
 * Form alterations.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Template\AttributeArray;

/**
 * Implements hook_form_alter().
 */
function civic_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  // Adjusting exposed view forms to hide submit button.
  if ($form['#form_id'] === 'views_exposed_form') {
    // Determines whether we render a large filter or a basic filter.
    // If the view exposed form has more than one filter we render a large
    // filter.
    $is_civic_large_filter = _civic_get_number_of_exposed_form_elements($form) > 1;
    $form['#attributes']['data-civic-filter-type'] = $is_civic_large_filter ? 'large' : 'basic';
    $form['#attributes']['data-civic-filter'] = '';
    $form['#large_filter'] = $is_civic_large_filter;
    $ajax_filter = $form_state->get('view')->ajaxEnabled();
    $form['#attributes']['data-civic-filter-ajax'] = $ajax_filter ? 'true' : 'false';
    // Ajax exposed view forms hide the submit to have auto-updates.
    if ($ajax_filter) {
      $form['actions']['submit']['#attributes']['class'][] = 'hide-xs';
    }
    else {
      $form['actions']['submit']['#attributes']['class'] = [
        'civic-button--secondary',
        'civic-button--small',
      ];
    }
    foreach ($form as $key => &$element) {
      if (strpos($key, '#') === 0 || empty($element['#type'])) {
        continue;
      }
      _civic_form_alter__convert_exposed_form_elements($element, $form, $is_civic_large_filter);
    }
  }
}

/**
 * Counts how many filters are in the form.
 */
function _civic_get_number_of_exposed_form_elements($form) {
  $num_of_elements = 0;
  foreach ($form as $key => &$element) {
    if (strpos($key, '#') === 0 || empty($element['#type'])
      || in_array($element['#type'], ['actions', 'hidden'])) {
      continue;
    }
    $num_of_elements++;
  }

  return $num_of_elements;
}

/**
 * Convert form elements to correct civic filter format.
 */
function _civic_form_alter__convert_exposed_form_elements(&$element, $form, $is_large_filter) {
  // Convert select to either radio buttons or checkboxes.
  if ($element['#type'] === 'select') {
    if (!empty($element['#multiple'])) {
      $element['#type'] = 'checkboxes';
      unset($element['#multiple']);
    }
    else {
      $element['#type'] = 'radios';
    }
  }
  // Only radio buttons and checkboxes work with filter chips.
  $filter_key = !$is_large_filter && in_array($element['#type'], [
    'radios',
    'checkboxes',
  ]) ? '#civic-basic-filter' : '#civic-large-filter';
  $element[$filter_key] = $form['#id'];
  $element['#attributes'][$filter_key] = $form['#id'];
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
  $element = $variables['element'];
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
  if (isset($element['#civic-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['filter_group'] = $element['#civic-large-filter'];
    if (isset($variables['description'])) {
      unset($variables['description']);
    }
  }
  elseif (isset($element['#attributes']['#civic-basic-filter'])) {
    $variables['text'] = $element['#title'] ?? '';
    $variables['is_multiple'] = $element['#type'] === 'checkbox';
    $variables['id'] = $element['#attributes']['id'];
    $variables['name'] = $element['#attributes']['name'];
    unset($element['#attributes']['name']);
    unset($element['#attributes']['id']);
    unset($element['#attributes']['#civic-basic-filter']);
    $variables['attributes'] = new Attribute($element['#attributes']);
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
  _civic_theme_suggestions_civic_filter($suggestions, $variables);
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civic_theme_suggestions_form_element_alter(array &$suggestions, array $variables, $type = NULL) {
  _civic_theme_suggestions_civic_filter($suggestions, $variables, 'form_element');
  // For civic basic filter both radios / checkboxes and checkbox / radio
  // require custom templates.
  if (in_array($variables['element']['#type'], ['radio', 'checkbox'])
    && isset($variables['element']['#attributes']['#civic-basic-filter'])) {
    $suggestions[] = 'form_element__civic_filter_chip';
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function civic_theme_suggestions_radios_alter(array &$suggestions, array $variables) {
  _civic_theme_suggestions_civic_filter($suggestions, $variables);
}

/**
 * Sets theme suggestions of civic filter elements.
 */
function _civic_theme_suggestions_civic_filter(array &$suggestions, array $variables, $type = NULL) {
  if (empty($variables['element']['#type'])) {
    return;
  }
  $type = $type ?? $variables['element']['#type'];
  if (!empty($variables['element']['#civic-large-filter'])) {
    $suggestions[] = $type . '__civic_dropdown_filter';
  }
  elseif (!empty($variables['element']['#civic-basic-filter'])) {
    $suggestions[] = $type . '__civic_basic_filter';
  }
}

/**
 * Implements template_preprocess_checkboxes().
 */
function civic_preprocess_checkboxes(&$variables) {
  _civic_preprocess_composite_form_elements($variables);
}

/**
 * Implements template_preprocess_radios().
 */
function civic_preprocess_radios(&$variables) {
  _civic_preprocess_composite_form_elements($variables);
}

/**
 * Helper function to preprocess radios and checkboxes composite form elements.
 */
function _civic_preprocess_composite_form_elements(&$variables) {
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
    $variables['errors'] = $element['#errors'] ?? NULL;
    $variables['attributes'] += $element['#attributes'];
    $attributes = new Attribute($variables['attributes']);
    $attributes = implode(' ', $attributes->getClass()->value());
    $variables['modifier_class'] = !empty($element['#attributes']['class']) ? implode(' ', $variables['element']['#attributes']['class']) : '';
    $variables['modifier_class'] = sprintf('%s %s %s %s', $variables['modifier_class'], $attributes, 'js-form-type-' . $element['#type'], $variables['required'] ? 'required' : '');
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

  if (isset($types['radios'])) {
    // Radios adds a wrapping fieldset to the checkboxes element
    // which we do not want when rendering dropdown filters.
    // The fieldset wrapper is added back manually for all other fieldsets.
    // @see civic_preprocess_checkboxes
    unset($types['radios']['#pre_render']);
  }
}
