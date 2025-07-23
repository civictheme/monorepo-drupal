<?php

/**
 * @file
 * Form elements.
 *
 * @phpcs:disable Drupal.Files.LineLength.TooLong
 *
 * Drupal uses 'form element' as a unit of rendering for form elements: it is
 * a wrapper around specific form controls (input, select, textarea, etc.).
 *
 * For some form elements, like checkboxes and radios, the form element is
 * rendered as a fieldset element first, and then the actual form controls are
 * rendered inside the fieldset element.
 *
 * CivicTheme uses slightly different approach to render form elements: it uses
 * a centralised template (factory) called "Field" which is responsible for
 * rendering form elements in a consistent way - this allows to strictly align
 * form elements with the design system.
 *
 * The code below implements the necessary preprocessors to redirect rendering
 * to the dedicated form element template and allow to preprocess form elements
 * based on their type.
 *
 * Note that sub-themes can opt-out from this behavior by implementing
 * hook_theme_suggestions_form_element_alter() and
 * hook_theme_suggestions_fieldset_alter() and removing the suggestions
 * added here based on the required conditions.
 *
 * A render pipeline for form_element and fieldset looks like this:
 *
 * +-------------------------------------------------+
 * | civictheme_theme_suggestions_form_element_alter |
 * | civictheme_theme_suggestions_fieldset_alter     |
 * |    - Check element type                         |
 * |    - Add suggestions                            |
 * +-------------------------------------------------+
 *          |
 *          v
 * +----------------------------------------------------+
 * | Theme Hook Suggestions Generated                   |
 * | - form_element__civictheme_field                   |
 * | - form_element__civictheme_field__<type>           |
 * | - fieldset__form_element__civictheme_field         |
 * | - fieldset__form_element__civictheme_field__<type> |
 * +----------------------------------------------------+
 *          |
 *          v
 * +-----------------------------------------------------+
 * | Sub-theme hook_theme_suggestions_form_element_alter |
 * |    - Check opt-out condition                        |
 * |    - Remove custom suggestions if opted out         |
 * +-----------------------------------------------------+
 *          |                             |
 *          v                             v
 * +------------------------+   +------------------------+      +------------------------+
 * | Not Opted-out          |   | Opted-out Condition    |  ->  | Core Template Render   |
 * | - Use custom templates |   | - Use core templates   |      | - form-element         |
 * +------------------------+   +------------------------+      | - fieldset             |
 *          |                                                   | - input--submit        |
 *          |                                                   +------------------------+
 *          v
 * +--------------------------------------------------------------------------+
 * | Preprocess Function Selection                                            |
 * | - form_element__civictheme_field                                         |
 * | - form_element__civictheme_field__<type>                                 |
 * | - civictheme_preprocess_fieldset__form_element__civictheme_field         |
 * | - civictheme_preprocess_fieldset__form_element__civictheme_field__<type> |
 * +--------------------------------------------------------------------------+
 *          |
 *          v
 * +----------------------------------------------------------------+
 * | Custom Template Render                                         |
 * |                                                                |
 * | - form-element--civictheme-field.html.twig                     |
 * |   All non-group components using CivicTheme's Field component. |
 * |                                                                |
 * | - form-element--civictheme-field--<type>.html.twig             |
 * |   Reserved for sub-theme overrides to allow using custom       |
 * |   components that may or may not be bases of CivicTheme's      |
 * |   Field component.                                             |
 * |                                                                |
 * | - fieldset--form-element--civictheme-field.html.twig           |
 * |   All group components using CivicTheme's Field component.     |
 * |                                                                |
 * | - fieldset.html.twig                                           |
 * |   <fieldset> using CivicTheme's Fieldset component.            |
 * +----------------------------------------------------------------+
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_theme_suggestions_form_element_alter().
 */
function civictheme_theme_suggestions_form_element_alter(array &$suggestions, array $variables) {
  // Redirect rendering to a dedicated form element template and allow to
  // preprocess form elements based on their type.
  //
  // Note that sub-themes can opt-out from this behavior by implementing
  // hook_theme_suggestions_form_element_alter() and removing the suggestions
  // added here based on some conditions.
  // @see _civictheme_supported_form_element() for a list of supported types.
  $supported_form_element = isset($variables['element']['#type']) && _civictheme_supported_form_element($variables['element']);
  if ($supported_form_element) {
    $suggestions[] = 'form_element__civictheme_field';
    $suggestions[] = 'form_element__civictheme_field__' . $variables['element']['#type'];
  }
}

/**
 * Check whether form render element is supported directly by CivicTheme.
 *
 * @param array $form_element
 *   The form element.
 *
 *   This list has been derived with testing elements that extend
 *   \Drupal\Core\Render\Element\FormElement.
 *
 * @return bool
 *   Whether the form element is directly supported by CivicTheme.
 */
function _civictheme_supported_form_element(array $form_element): bool {
  // Form elements can be explictly opted with `#civictheme_form_element`
  // property.
  if (!empty($form_element[CivicthemeConstants::CIVICTHEME_FORM_ELEMENT_PROPERTY])) {
    return TRUE;
  }
  // This is a list of form elements that CivicTheme works with currently
  // This list may change with changes to Drupal and updates to CivicTheme.
  $form_element_type = $form_element['#type'];
  $supported_core_types = [
    'button',
    'checkbox',
    'checkboxes',
    'color',
    'date',
    'email',
    'entity_autocomplete',
    'file',
    'image_button',
    'linkit',
    'machine_name',
    'number',
    'password',
    'path',
    'radio',
    'radios',
    'search',
    'select',
    'tableselect',
    'textarea',
    'textfield',
    'url',
    'weight',
  ];

  $supported_webform_types = [
    'tel',
    'webform_date',
    'webform_email',
    'webform_number',
    'webform_select',
    'webform_textarea',
    'webform_time',
    'webform_url',
  ];

  $supported_types = array_merge($supported_core_types, $supported_webform_types);

  return in_array($form_element_type, $supported_types);
}

/**
 * Implements hook_theme_suggestions_fieldset_alter().
 */
function civictheme_theme_suggestions_fieldset_alter(array &$suggestions, array $variables) {
  // Checkboxes and radios are rendered through a fieldset element. We need to
  // redirect the rendering to a dedicated form element template, but only for
  // supported types to preserve the default rendering for other types of
  // a fieldset.
  //
  // Note that sub-themes can opt-out from this behavior by implementing
  // hook_theme_suggestions_fieldset_alter() and removing the suggestions
  // added here based on some conditions.
  $supported_types = ['checkboxes', 'radios'];
  if (isset($variables['element']['#type']) && in_array($variables['element']['#type'], $supported_types)) {
    $suggestions[] = 'fieldset__form_element__civictheme_field';
    $suggestions[] = 'fieldset__form_element__civictheme_field__' . $variables['element']['#type'];
  }
}

/**
 * Implements template_preprocess_form_element().
 */
function civictheme_preprocess_form_element__civictheme_field(array &$variables): void {
  _civictheme_preprocess_form_element__generic($variables);
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_preprocess_form_element__classes($variables);
  _civictheme_preprocess_form_element__description($variables);
  _civictheme_preprocess_form_element__control($variables);
}

/**
 * Implements template_preprocess_form_element().
 */
function civictheme_preprocess_form_element__civictheme_field__select(array &$variables): void {
  $variables['control'][0]['options'] = form_select_options($variables['element']);

  foreach ($variables['control'][0]['options'] as &$option) {
    if (isset($option['selected'])) {
      $option['is_selected'] = $option['selected'];
      unset($option['selected']);
    }
    if (isset($option['disabled'])) {
      $option['is_disabled'] = $option['disabled'];
      unset($option['disabled']);
    }
  }
  unset($option);
  $variables['control'][0]['is_multiple'] = _civictheme_form_element_is_multiple($variables['element']);
}

/**
 * Implements template_preprocess_fieldset().
 */
function civictheme_preprocess_fieldset__form_element__civictheme_field(array &$variables): void {
  _civictheme_preprocess_form_element__generic($variables);
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_preprocess_form_element__wrapper_classes($variables);
  _civictheme_preprocess_form_element__classes($variables);
  _civictheme_preprocess_form_element__description($variables);
  _civictheme_preprocess_form_element__control($variables);
}

/**
 * Implements template_preprocess_fieldset().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_fieldset__form_element__civictheme_field__checkboxes(array &$variables): void {
  $element = $variables['element'];

  // Retrieve attributes and modifier class populated by other preprocessors.
  $attributes = $variables['control'][0]['attributes'] ?? '';
  $modifier_class = $variables['control'][0]['modifier_class'] ?? '';

  // Prepare control array.
  $control = [];
  foreach ($element['#options'] as $value => $label) {
    $is_checked = is_array($element['#value']) ? in_array($value, $element['#value']) : $element['#value'] == $value;
    $name = $element['#attributes']['name'] ?? ($element['#name'] ?? '');
    $control[] = [
      'label' => $label,
      'value' => $value,
      'name' => $name . '[' . $value . ']',
      'id' => Html::cleanCssIdentifier($element['#id'] . '-' . $value),
      'is_checked' => $is_checked,
      'is_disabled' => isset($element['#attributes']['disabled']),
      // Checkboxes controls with a group cannot be required by definition.
      'is_required' => FALSE,
      'attributes' => $attributes,
      'modifier_class' => $modifier_class,
    ];
  }

  $variables['control'] = $control;

  $variables['is_inline'] = isset($element['#options_display']) && ($element['#options_display'] === 'inline' || $element['#options_display'] === 'buttons_horizontal');
  $variables['attributes']['id'] = $variables['element']['#id'] ?? '';
  $variables['type'] = 'checkbox';
}

/**
 * Implements template_preprocess_fieldset().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
function civictheme_preprocess_fieldset__form_element__civictheme_field__radios(array &$variables): void {
  $element = $variables['element'];

  // Retrieve attributes and modifier class populated by other preprocessors.
  $attributes = $variables['control'][0]['attributes'] ?? '';
  $modifier_class = $variables['control'][0]['modifier_class'] ?? '';

  // Prepare control array.
  $control = [];
  foreach ($element['#options'] as $value => $label) {
    $name = $element['#attributes']['name'] ?? ($element['#name'] ?? '');
    $selected_value = $element['#value'] ?? NULL;
    // Cannot do strict value check as integer values returned as string.
    $is_checked = $value == $selected_value;
    $control[] = [
      'label' => $label,
      'value' => $value,
      'name' => $name,
      'id' => Html::cleanCssIdentifier($element['#id'] . '-' . $value),
      'is_checked' => $is_checked,
      'is_disabled' => isset($element['#attributes']['disabled']),
      'attributes' => $attributes,
      'modifier_class' => $modifier_class,
    ];
  }

  $variables['control'] = $control;

  $variables['is_inline'] = isset($element['#options_display']) && ($element['#options_display'] === 'inline' || $element['#options_display'] === 'buttons_horizontal');
  // We do not use the inner div element for radios and checkboxes.
  // For error messages anchors we need to use the wrapper id and not the
  // wrapper.
  if (!empty($variables['attributes']['id']) && str_ends_with($variables['attributes']['id'], '--wrapper')) {
    $variables['attributes']['id'] = str_replace('--wrapper', '', $variables['attributes']['id']);
  }
  $variables['type'] = 'radio';
}

/**
 * Implements template_preprocess_fieldset().
 */
function civictheme_preprocess_fieldset(array &$variables): void {
  $element = $variables['element'];

  if ($element['#type'] !== 'fieldset') {
    return;
  }

  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_preprocess_form_element__classes($variables);
  _civictheme_preprocess_form_element__description($variables);
}

/**
 * Implements template_preprocess_input().
 */
function civictheme_preprocess_input__submit(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);

  $type = 'primary';
  if (is_array($variables['attributes']['class']) && in_array('secondary', $variables['attributes']['class'])) {
    $type = 'secondary';
  }
  $variables['type'] = $type;

  $variables['value'] = $variables['attributes']['value'] ?? t('Submit');
}

/**
 * Preprocesses a generic form element.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_preprocess_form_element__generic(array &$variables): void {
  $element = $variables['element'];
  $variables['type'] = $element['#type'] ?? 'input';

  $variables['title'] = $element['#title'] ?? '';

  $title_display = $element['#_title_display'] ?? $element['#title_display'] ?? $variables['title_display'] ?? 'visible';
  $title_display = $title_display === 'none' ? 'hidden' : $title_display;
  $title_display = $title_display === 'visually_hidden' ? 'invisible' : $title_display;
  if (empty($variables['title'])) {
    $title_display = 'hidden';
  }
  $variables['title_display'] = $title_display;

  $variables['orientation'] = $variables['orientation'] ?? $title_display === 'inline' ? 'horizontal' : 'vertical';

  $variables['is_required'] = $element['#required'] ?? FALSE;
  $variables['is_disabled'] = isset($element['#attributes']['disabled']);
  $variables['description'] = $element['#description'] ?? '';
  $variables['is_invalid'] = !empty($element['#errors']);
  if ($variables['is_invalid']) {
    $variables['attributes']['aria-invalid'] = 'true';
    $variables['message']['content'] = $element['#errors'] ?? '';
    $element_id = $element['#id'] ?? $element['#attributes']['data-drupal-selector'] ?? '';
    if (!empty($element_id)) {
      $element_id .= '--error-message';
      $variables['message']['attributes'] = new Attribute(['id' => $element_id]);
      $variables['attributes']['aria-describedby'] = $element_id;
    }
  }
  $variables['placeholder'] = $element['#placeholder'] ?? $element['#attributes']['placeholder'] ?? '';
  $variables['prefix'] = $element['#field_prefix'] ?? $element['#prefix'] ?? '';
  $variables['suffix'] = $element['#field_suffix'] ?? $element['#suffix'] ?? '';
}

/**
 * Preprocesses a form element control.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_preprocess_form_element__control(array &$variables): void {
  $element = $variables['element'];

  $allowed_attributes = ['size', 'maxlength', 'rows', 'autocomplete'];

  $attributes = [];

  $allowed_attributes_map = array_map(function ($attribute) {
    return '#' . $attribute;
  }, $allowed_attributes);

  foreach ($allowed_attributes_map as $key) {
    if (isset($element[$key])) {
      $attributes[substr($key, 1)] = $element[$key];
    }
  }

  if (!empty($element['#attributes']) && is_iterable($element['#attributes'])) {
    foreach ($element['#attributes'] as $key => $value) {
      if (str_starts_with($key, 'data') || in_array($key, $allowed_attributes)) {
        $attributes[$key] = is_array($value) ? implode(' ', $value) : $value;
      }
    }
  }

  $modifier_class = $element['#attributes']['class'] ?? [];
  $modifier_class[] = 'form-element';
  $modifier_class[] = 'form-element--type-' . $variables['type'];
  $modifier_class[] = 'form-element--api-' . $element['#type'];
  $modifier_class[] = 'form-control';
  if (in_array($variables['type'], ['checkbox', 'radio'])) {
    $modifier_class[] = 'form-boolean';
    $modifier_class[] = Html::getClass('form-boolean--type-' . $variables['type']);
  }
  if (is_array($modifier_class)) {
    $modifier_class = implode(' ', $modifier_class);
  }

  $attributes = new Attribute($attributes);

  $variables['control'][] = [
    'name' => $element['#attributes']['name'] ?? ($element['#name'] ?? ''),
    'value' => $element['#value'] ?? '',
    'id' => $element['#id'] ?? '',
    'options' => $variables['options'] ?? [],
    'is_multiple' => $variables['is_multiple'] ?? FALSE,
    'attributes' => $attributes,
    'modifier_class' => $modifier_class,
  ];

  // Set control name for search elements if not already set.
  if (
    empty($variables['control'][0]['name']) &&
    $element['#type'] === 'search' &&
    !empty($element['#id'])
  ) {
    $variables['control'][0]['name'] = $element['#id'];
  }

  if ($element['#type'] === 'checkbox') {
    $variables['control'][0]['is_checked'] = $element['#value'] ?? FALSE;
    $variables['control'][0]['is_checked'] = (bool) $variables['control'][0]['is_checked'];
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
  $variables['attributes']['class'][] = 'form-item';

  $variables['attributes']['class'][] = 'js-form-item';

  if (!empty($variables['type'])) {
    $variables['attributes']['class'][] = 'js-form-type-' . Html::getClass($variables['type']);
    $variables['attributes']['class'][] = 'form-type-' . Html::getClass($variables['type']);
    $variables['attributes']['class'][] = 'form-type--' . Html::getClass($variables['type']);
  }

  if (!empty($variables['name'])) {
    $variables['attributes']['class'][] = 'js-form-item-' . Html::getClass($variables['name']);
    $variables['attributes']['class'][] = 'form-item-' . Html::getClass($variables['name']);
    $variables['attributes']['class'][] = 'form-item--' . Html::getClass($variables['name']);
  }
  elseif (!empty($variables['element']['#name'])) {
    $variables['attributes']['class'][] = 'js-form-item-' . Html::getClass($variables['element']['#name']);
    $variables['attributes']['class'][] = 'form-item-' . Html::getClass($variables['element']['#name']);
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
 * Preprocesses form element wrapper classes.
 */
function _civictheme_preprocess_form_element__wrapper_classes(array &$variables): void {
  if (!empty($variables['element']['#wrapper_attributes'])) {
    $variables['attributes'] = $variables['element']['#wrapper_attributes'];
  }
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
