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
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function civic_form_views_exposed_form_alter(&$form, FormStateInterface $form_state) {
  // Determine whether we render a large filter or a basic filter.
  // If the view exposed form has more than one filter we render a large
  // filter.
  $is_civic_large_filter = _civic_get_number_of_exposed_form_elements($form) > 1;
  $form['#attributes']['data-civic-filter-type'] = $is_civic_large_filter ? 'large' : 'basic';
  $form['#attributes']['data-civic-filter'] = '';
  $form['#large_filter'] = $is_civic_large_filter;
  $ajax_filter = $form_state->get('view')->ajaxEnabled();
  $form['#attributes']['data-civic-filter-ajax'] = $ajax_filter ? 'true' : 'false';
  // Ajax exposed view forms hides submit button to have auto-load.
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
 * Alter form elements to work within civic large and basic filters.
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
    _civic_form_alter__modify_element_callbacks($element);
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

  _civic_preprocess_civic_filter_form_element_alter($variables);
}

/**
 * Helper to clear pre_render and process callbacks.
 *
 * Since we are converting select to checkboxes or radios we need to modify the
 * pre_render and process callbacks that do not relate to new inputs.
 */
function _civic_form_alter__modify_element_callbacks(&$element) {
  $element_class = $element['#type'] === 'radios' ? '\Drupal\Core\Render\Element\Radios' : '\Drupal\Core\Render\Element\Checkboxes';

  // Unsetting pre-render as we do not need these.
  if (!empty($element['#pre_render'])) {
    foreach ($element['#pre_render'] as $index => $callback) {
      if (is_array($callback) && $callback[0] === 'Drupal\Core\Render\Element\Select') {
        unset($element['#pre_render'][$index]);
      }
    }
  }

  // Modifying the ajax callbacks.
  if (!empty($element['#pre_render'])) {
    foreach ($element['#process'] as $index => $callback) {
      if (is_array($callback) && $callback[0] === 'Drupal\Core\Render\Element\Select') {
        if (method_exists($element_class, $callback[1])) {
          $element['#process'][$index] = [
            $element_class,
            $callback[1],
          ];
        }
        else {
          unset($element['#process'][$index]);
        }
      }
    }
  }
}

/**
 * Pre-process form elements used in civic large and basic filters.
 */
function _civic_preprocess_civic_filter_form_element_alter(&$variables) {
  $element = $variables['element'];
  if (isset($element['#civic-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['is_inline'] = TRUE;
    $variables['inline_change_breakpoint'] = '>=m';
    $variables['filter_group'] = $element['#civic-large-filter'];
    if (isset($variables['description'])) {
      unset($variables['description']);
    }
  }
  elseif (isset($element['#attributes']['#civic-large-filter'])) {
    if (isset($element['#type']) && in_array($element['#type'], [
      'radio',
      'checkbox',
    ])) {
      $variables['#attributes']['data-dropdown-filter-item'] = '';
      $variables['attributes'] = new Attribute($variables['#attributes']);
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
  $variables['content'] = $variables['title'] ?? '';

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
  $is_radio_or_checkbox = FALSE;
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
 * Helper function to pre-process radios and checkboxes composite form elements.
 */
function _civic_preprocess_composite_form_elements(&$variables) {
  $element = &$variables['element'];
  if (isset($element['#civic-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['is_inline'] = TRUE;
    $variables['inline_change_breakpoint'] = '>=m';
    $variables['filter_group'] = $element['#civic-large-filter'];
    $variables['#attributes']['data-dropdown-filter-fieldset'] = '';
    if (!empty($variables['attributes'])) {
      $variables['#attributes'] += $variables['attributes'];
      $variables['attributes'] = new Attribute($variables['#attributes']);
      foreach ($variables['element']['#attributes'] as $attribute_name => $attribute_value) {
        $variables['attributes']->setAttribute($attribute_name, $attribute_value);
      }
    }
    $variables['searchable_threshold'] = 8;
    $variables['search_label_text'] = t('Filter by Keyword');
  }
  else {
    // Standard theme wrapper for checkboxes was removed to allow for
    // civic large filter checkboxes and so we need to add it back as a
    // pre-process function as we cannot add a pre_render function at theme
    // level.
    $variables['required'] = $element['#required'] ?? FALSE;
    $variables['type'] = 'fieldset-radio';
    $variables['fieldset_legend'] = $element['#title'] ?? '';
    $variables['errors'] = $element['#errors'] ?? NULL;
    if (!empty($element['#attributes'])) {
      $variables['attributes'] += $element['#attributes'];
    }
    $attributes = new Attribute($variables['attributes']);
    $attributes = $attributes->getClass() !== NULL ? implode(' ', $attributes->getClass()->value()) : '';
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
