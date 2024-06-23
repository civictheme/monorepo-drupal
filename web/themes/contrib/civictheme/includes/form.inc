<?php

/**
 * @file
 * Form alterations.
 */

declare(strict_types=1);

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements hook_form_alter().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_form_alter(array &$form, FormStateInterface $form_state, string $form_id): void {
  _civictheme_form_alter__webform_submission($form, $form_state, $form_id);
  _civictheme_form_alter__non_form_elements($form);
}

/**
 * Form alter for webform submission form.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_alter__webform_submission(array &$form, FormStateInterface $form_state, string $form_id): void {
  if (!empty($form_id) && str_starts_with($form_id, 'webform_submission_')) {
    /** @var \Drupal\webform\WebformSubmissionForm $form_object */
    $form_object = $form_state->getFormObject();

    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $form_object->getEntity();
    $source = $webform_submission->getSourceEntity();

    $paragraph_id = empty($source) ? '' : $source->id();
    if (!empty($paragraph_id) && !empty($form['elements'])) {
      $theme = civictheme_get_field_theme_value(Paragraph::load($paragraph_id));
      _civictheme_form_element_propagate_component_theme($form['elements'], $theme);
    }
  }
}

/**
 * Alter elements in forms that are not categorised as form elements.
 *
 * Form elements should be preprocessed in an associated preprocess functions.
 *
 * However, there area some types of Element in form are not form elements eg
 * link and html_tag. This function provides the ability to alter these elements
 * if required.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_alter__non_form_elements(array &$elements): void {
  $element_keys = Element::children($elements);

  foreach ($element_keys as $element_key) {
    if (empty($elements[$element_key]['#type'])) {
      continue;
    }

    $nested_elements = Element::children($elements[$element_key]);
    if (!empty($nested_elements)) {
      _civictheme_form_alter__non_form_elements($elements[$element_key]);
    }

    // Add elements to modify to this switch.
    switch ($elements[$element_key]['#type']) {
      case 'link':
        _civictheme_form_alter__non_form_elements__link($elements[$element_key]);
        break;

      default:
        continue 2;
    }
  }
}

/**
 * Alter link elements within forms.
 */
function _civictheme_form_alter__non_form_elements__link(array &$element): void {
  // Convert "button" links into CivicTheme button links.
  if (!empty($element['#attributes']['class']) && in_array('button', $element['#attributes']['class'])) {
    $element_theme = _civictheme_form_element_get_component_theme($element);
    $element['#attributes']['class'] = array_merge($element['#attributes']['class'], [
      'ct-button',
      'ct-button--regular',
      'ct-button--secondary',
      'ct-theme-' . $element_theme,
    ]);
  }
}

/**
 * Preprocess hidden form elements.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_preprocess_hidden_form_elements(array $variables): void {
  if (empty($variables['form'])) {
    return;
  }

  $form = &$variables['form'];

  $field_names = [
    'form_build_id',
    'form_id',
    'form_token',
  ];

  $output = '';
  foreach (Element::children($form) as $field_name) {
    if (in_array($field_name, $field_names)) {
      $els = [$form[$field_name]];
      $output .= civictheme_render($els);
    }
  }

  $variables['form_hidden_fields'] = $output;
}

/**
 * Get from elements the form without hidden fields.
 *
 * @param array $form
 *   Form array.
 *
 * @return array<string, mixed>
 *   Array of fields.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_get_form_elements(array $form): array {
  $exclude = [
    'form_build_id',
    'form_id',
    'form_token',
    'actions',
  ];

  $field_names = Element::children($form);

  return array_intersect_key($form, array_flip(array_diff($field_names, $exclude)));
}
