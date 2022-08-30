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
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_input().
 */
function civictheme_preprocess_input(&$variables) {
  _civictheme_get_theme_from_attributes($variables);
  $variables['state'] = 'none';
  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);

    if (in_array('error', $variables['attributes']['class'])) {
      $variables['state'] = 'error';
    }

    unset($variables['attributes']['class']);
  }

  if (isset($variables['attributes']['#civictheme_theme'])) {
    unset($variables['attributes']['#civictheme_theme']);
  }
}

/**
 * Implements template_preprocess_select().
 */
function civictheme_preprocess_select(&$variables) {
  _civictheme_get_theme_from_attributes($variables);

  if (isset($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
}

/**
 * Implements template_preprocess_form_element().
 */
function civictheme_preprocess_form_element(&$variables) {
  _civictheme_get_theme_from_attributes($variables);
  _civictheme_preprocess_form_element_classes($variables);
  _civictheme_preprocess_form_element_label($variables);
  _civictheme_preprocess_civictheme_filter_form_element_alter($variables);
}

/**
 * Preprocesses form element classes.
 */
function _civictheme_preprocess_form_element_classes(&$variables) {
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

  if (isset($variables['attributes']['class']) && is_array($variables['attributes']['class'])) {
    $variables['modifier_class'] = implode(' ', $variables['attributes']['class']);
    unset($variables['attributes']['class']);
  }
}

/**
 * Preprocesses form element label.
 */
function _civictheme_preprocess_form_element_label(&$variables) {
  if (isset($variables['description'])) {
    if (empty($variables['description']['attributes'])) {
      $variables['description']['attributes'] = new Attribute();
    }
    $variables['description']['attributes']->addClass('description');
  }

  if (isset($variables['description']['attributes']['class']) && $variables['description']['attributes']['class'] instanceof AttributeArray) {
    $variables['description_modifier_class'] = implode(' ', $variables['description']['attributes']['class']->value());
  }

  if (isset($variables['element']['#description'])) {
    $variables['description']['content'] = $variables['element']['#description'];
  }

  if (isset($variables['element']['#description_display'])) {
    $variables['description_position'] = $variables['element']['#description_display'];
  }
}

/**
 * Helper to clear pre_render and process callbacks.
 *
 * Since we are converting select to checkboxes or radios we need to modify the
 * pre_render and process callbacks that do not relate to new inputs.
 */
function _civictheme_filter_views_exposed_form_alter__modify_element_callbacks(&$element) {
  $element_class = $element['#type'] === 'radios' ? '\Drupal\Core\Render\Element\Radios' : '\Drupal\Core\Render\Element\Checkboxes';

  // Unsetting pre-render as we do not need these.
  $pre_render_callbacks = $element['#pre_render'] ?? [];
  foreach ($pre_render_callbacks ?? [] as $index => $callback) {
    if (is_array($callback) && $callback[0] === 'Drupal\Core\Render\Element\Select') {
      unset($element['#pre_render'][$index]);
    }
  }

  // Modifying the ajax callbacks.
  $process_callbacks = $element['#process'] ?? [];
  foreach ($process_callbacks as $index => $callback) {
    if (is_array($callback) && $callback[0] === 'Drupal\Core\Render\Element\Select') {
      if (method_exists($element_class, $callback[1])) {
        $element['#process'][$index] = [
          $element_class,
          $callback[1],
        ];
        continue;
      }
      unset($element['#process'][$index]);
    }
  }
}

/**
 * Pre-process form elements used in civictheme large and basic filters.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_civictheme_filter_form_element_alter(&$variables) {
  $element = $variables['element'];
  if (isset($element['#civictheme-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['is_inline'] = FALSE;
    $variables['inline_change_breakpoint'] = '>=m';
    $variables['filter_group'] = $element['#civictheme-large-filter'];
    if (isset($variables['description'])) {
      unset($variables['description']);
    }
  }
  elseif (isset($element['#attributes']['#civictheme-large-filter'])) {
    if (
      isset($element['#type'])
      && in_array($element['#type'], ['radio', 'checkbox'])
    ) {
      $variables['#attributes']['data-dropdown-filter-item'] = '';
      $variables['attributes'] = new Attribute($variables['#attributes']);
    }
  }
  elseif (isset($element['#attributes']['#civictheme-basic-filter'])) {
    $variables['text'] = $element['#title'] ?? '';
    $variables['is_multiple'] = $element['#type'] === 'checkbox';
    $variables['id'] = $element['#attributes']['id'];
    $variables['name'] = $element['#attributes']['name'];
    unset($element['#attributes']['name']);
    unset($element['#attributes']['id']);
    unset($element['#attributes']['#civictheme-basic-filter']);
    if (!empty($element['#attributes']['checked'])) {
      $variables['is_selected'] = TRUE;
    }
    $variables['attributes'] = new Attribute($element['#attributes']);
  }
}

/**
 * Implements template_preprocess_form_element_label().
 */
function civictheme_preprocess_form_element_label(&$variables) {
  _civictheme_get_theme_from_attributes($variables);
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
function civictheme_preprocess_fieldset(&$variables) {
  _civictheme_preprocess_fieldset_attributes($variables);
  _civictheme_preprocess_fieldset_type($variables);
}

/**
 * Sets fieldset form element type.
 */
function _civictheme_preprocess_fieldset_type(&$variables) {
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
}

/**
 * Sets attributes and class values on fieldset.
 */
function _civictheme_preprocess_fieldset_attributes(&$variables) {
  _civictheme_get_theme_from_attributes($variables);
  // Add core drupal form classes.
  $variables['attributes']['class'] = $variables['attributes']['class'] ?? [];
  $variables['attributes']['class'] = array_merge($variables['attributes']['class'], [
    'js-form-item',
    'form-item',
    'js-form-wrapper',
    'form-wrapper',
  ]);
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
function civictheme_preprocess_checkboxes(&$variables) {
  _civictheme_preprocess_composite_form_elements($variables);
}

/**
 * Implements template_preprocess_radios().
 */
function civictheme_preprocess_radios(&$variables) {
  _civictheme_preprocess_composite_form_elements($variables);
}

/**
 * Helper function to pre-process radios and checkboxes composite form elements.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_composite_form_elements(&$variables) {
  $element = &$variables['element'];
  _civictheme_get_theme_from_attributes($variables);
  // Add theme to radio / checkbox inputs.
  $form_elements = Element::children($variables['element']);
  foreach ($form_elements as $form_element) {
    $variables['element'][$form_element]['#civictheme_theme'] = $variables['theme'] ?? NULL;
  }

  if (isset($element['#civictheme-large-filter'])) {
    $variables['type'] = $element['#type'];
    $variables['filter_text'] = $element['#title'];
    $variables['options_title'] = $element['#description'] ?? '';
    $variables['is_inline'] = FALSE;
    $variables['inline_change_breakpoint'] = '>=m';
    $variables['filter_group'] = $element['#civictheme-large-filter'];
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

    return;
  }
  // Standard theme wrapper for checkboxes was removed to allow for
  // civictheme large filter checkboxes and so we need to add it back as a
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

/**
 * Implements hook_element_info_alter().
 */
function civictheme_element_info_alter(array &$types) {
  if (isset($types['checkboxes'])) {
    // Checkboxes adds a wrapping fieldset to the checkboxes element
    // which we do not want when rendering dropdown filters.
    // The fieldset wrapper is added back manually for all other fieldsets.
    // @see civictheme_preprocess_checkboxes
    unset($types['checkboxes']['#pre_render']);
  }

  if (isset($types['radios'])) {
    // Radios adds a wrapping fieldset to the checkboxes element
    // which we do not want when rendering dropdown filters.
    // The fieldset wrapper is added back manually for all other fieldsets.
    // @see civictheme_preprocess_checkboxes
    unset($types['radios']['#pre_render']);
  }
}

/**
 * Sets form element theme based on attributes passed from parent components.
 */
function _civictheme_get_theme_from_attributes(&$variables) {
  // Setting theme for form element.
  if (isset($variables['element']['#attributes']['#civictheme_theme'])
    && in_array($variables['element']['#attributes']['#civictheme_theme'], [
      CIVICTHEME_THEME_LIGHT,
      CIVICTHEME_THEME_DARK,
    ])) {
    $variables['theme'] = $variables['element']['#attributes']['#civictheme_theme'];
    unset($variables['element']['#attributes']['#civictheme_theme']);
    if (isset($variables['element']['label']['#attributes'])) {
      $variables['element']['label']['#attributes']['#civictheme_theme'] = $variables['theme'];
    }
    if (isset($variables['label']['#attributes'])) {
      $variables['label']['#attributes']['#civictheme_theme'] = $variables['theme'];
    }

    return;
  }
}

/**
 * Implements hook_preprocess_form().
 */
function civictheme_preprocess_form(&$variables) {
  if (isset($variables['element']['#attributes']['civictheme_listing'])) {
    unset($variables['element']['#attributes']['civictheme_listing']);
  }
  if (isset($variables['attributes']['civictheme_listing'])) {
    unset($variables['attributes']['civictheme_listing']);
  }
}

/**
 * Implements hook_form_alter().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  if (!empty($form_id) && strpos($form_id, 'webform_submission_') === 0) {
    /** @var \Drupal\webform\WebformSubmissionForm $form_object */
    $form_object = $form_state->getFormObject();

    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $form_object->getEntity();
    $source = $webform_submission->getSourceEntity();

    $paragraph_id = !empty($source) ? $source->id() : '';
    if (!empty($paragraph_id) && !empty($form['elements'])) {
      $theme = civictheme_get_field_theme_value(Paragraph::load($paragraph_id));
      _civictheme_propagate_component_theme_settings($form['elements'], $theme);
    }
  }
}
