<?php

/**
 * @file
 * Form alterations.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;

/**
 * Implements template_preprocess_form_element().
 */
function civictheme_preprocess_form_element(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_preprocess_form_element__classes($variables);
  _civictheme_preprocess_form_element__description($variables);

  // @todo Remove this centralized processing is implemented.
  if ($variables['element']['#type'] == 'select') {
    $variables['options'] = form_select_options($variables['element']);
    $variables['is_multiple'] = $variables['element']['#multiple'] ?? FALSE;
  }
}

/**
 * Implements template_preprocess_form_element_label().
 */
function civictheme_preprocess_form_element_label(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
  $variables['content'] = $variables['title'] ?? '';

  // Adding in core template classes.
  if (!empty($variables['required'])) {
    $variables['attributes']['class'][] = 'js-form-required';
  }
}

/**
 * Implements template_preprocess_select().
 */
function civictheme_preprocess_select(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
}

/**
 * Implements template_preprocess_input().
 */
function civictheme_preprocess_input(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);

  $variables['state'] = 'none';

  if (isset($variables['attributes']['class']) && in_array('error', $variables['attributes']['class'])) {
    $variables['state'] = 'error';
  }

  if (isset($variables['attributes']['#civictheme_theme'])) {
    unset($variables['attributes']['#civictheme_theme']);
  }

  // Fix htmlentity in submit button text.
  $element = $variables['element'];

  if (isset($element['#type']) && in_array($element['#type'], ['submit', 'button'])) {
    $variables['value'] = $variables['attributes']['value'] ?? 'Submit';
  }
}

/**
 * Sets form element theme based on attributes passed from parent components.
 */
function _civictheme_preprocess_form_element__theme(array &$variables): void {
  // Setting theme for form element.
  if (
    isset($variables['element']['#attributes']['#civictheme_theme'])
    && in_array($variables['element']['#attributes']['#civictheme_theme'], civictheme_theme_options(TRUE))
  ) {
    $variables['theme'] = $variables['element']['#attributes']['#civictheme_theme'];
    unset($variables['element']['#attributes']['#civictheme_theme']);

    if (isset($variables['element']['label']['#attributes'])) {
      $variables['element']['label']['#attributes']['#civictheme_theme'] = $variables['theme'];
    }

    if (isset($variables['label']['#attributes'])) {
      $variables['label']['#attributes']['#civictheme_theme'] = $variables['theme'];
    }
  }
}

/**
 * Preprocesses form element classes.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_form_element__classes(array &$variables): void {
  $variables['attributes']['class'] = $variables['attributes']['class'] ?? [];
  $variables['attributes']['class'] = is_string($variables['attributes']['class']) ? [$variables['attributes']['class']] : $variables['attributes']['class'];

  // Add missing core Drupal form element classes that are added in template
  // file.
  // Note that we are only adding JS classes for 3rd-parties to bind to. Any
  // other classes added by core or contrib modules may affect the look and feel
  // of the form.
  $variables['attributes']['class'][] = 'js-form-item';

  if (!empty($variables['type'])) {
    $variables['attributes']['class'][] = 'js-form-type-' . Html::getClass($variables['type']);
  }

  if (!empty($variables['name'])) {
    $variables['attributes']['class'][] = 'js-form-item-' . Html::getClass($variables['name']);
  }

  if (!empty($variables['errors'])) {
    $variables['attributes']['class'][] = 'js-form-item--error';
  }
}

/**
 * Preprocesses form element description.
 */
function _civictheme_preprocess_form_element__description(array &$variables): void {
  $variables['description'] = $variables['element']['#description'] ?? NULL;
  $variables['description_display'] = $variables['element']['#description_display'] ?? NULL;
}

/**
 * Propagate component theme setting to nested form elements.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_element_propagate_component_theme(array &$elements, string $theme): void {
  $form_elements = Element::children($elements);
  foreach ($form_elements as $form_element) {
    $elements[$form_element]['#attributes']['#civictheme_theme'] = $theme;
    $elements[$form_element]['#attributes']['#component_theme'] = $theme;
    $children = Element::children($elements[$form_element]);
    if (!empty($children)) {
      _civictheme_form_element_propagate_component_theme($elements[$form_element], $theme);
    }
  }
}

/**
 * Get component theme from the form element.
 */
function _civictheme_form_element_get_component_theme(array $element): string {
  $theme = CivicthemeConstants::THEME_LIGHT;
  if (isset($element['#attributes']['#civictheme_theme'])
    && in_array($element['#attributes']['#civictheme_theme'], [
      CivicthemeConstants::THEME_LIGHT,
      CivicthemeConstants::THEME_DARK,
    ])) {
    $theme = $element['#attributes']['#civictheme_theme'];
  }

  return $theme;
}

/**
 * Check if form element is set to have multiple values.
 */
function _civictheme_form_element_is_multiple(array $element): bool {
  return $element['#type'] == 'checkboxes' || ($element['#type'] == 'select' && ($element["#multiple"] ?? FALSE));
}
