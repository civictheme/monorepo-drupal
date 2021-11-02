<?php

/**
 * @file
 * Form alterations.
 */

use Drupal\Core\Template\AttributeArray;

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
  if (isset($variables['attributes']['class'])) {
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
  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
}

/**
 * Implements template_preprocess_fieldset().
 */
function civic_preprocess_fieldset(&$variables) {
  $variables['type'] = $variables['type'] ?? 'fieldset';
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
