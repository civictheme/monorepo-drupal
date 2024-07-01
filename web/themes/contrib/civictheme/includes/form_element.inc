<?php

/**
 * @file
 * Form elements.
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
  $suggestions[] = 'form_element__civictheme_field';
  $suggestions[] = 'form_element__civictheme_field__' . $variables['element']['#type'];
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
    $name = isset($element['#attributes']['name']) ? $element['#attributes']['name'] : (isset($element['#name']) ? $element['#name'] : '');
    $control[] = [
      'label' => $label,
      'value' => $value,
      'name' => $name . '[' . $value . ']',
      'id' => Html::cleanCssIdentifier($element['#id'] . '-' . $value),
      'is_checked' => is_array($element['#default_value']) ? in_array($value, $element['#default_value']) : $element['#default_value'] == $value,
      'is_disabled' => isset($element['#attributes']['disabled']),
      'attributes' => $attributes,
      'modifier_class' => $modifier_class,
    ];
  }

  $variables['control'] = $control;

  $variables['is_inline'] = isset($element['#options_display']) && ($element['#options_display'] == 'inline' || $element['#options_display'] == 'buttons_horizontal');

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
    $name = isset($element['#attributes']['name']) ? $element['#attributes']['name'] : (isset($element['#name']) ? $element['#name'] : '');
    $control[] = [
      'label' => $label,
      'value' => $value,
      'name' => $name,
      'id' => Html::cleanCssIdentifier($element['#id'] . '-' . $value),
      'is_checked' => isset($element['#default_value']) ? (is_array($element['#default_value']) ? in_array($value, $element['#default_value']) : $element['#default_value'] == $value) : FALSE,
      'is_disabled' => isset($element['#attributes']['disabled']),
      'attributes' => $attributes,
      'modifier_class' => $modifier_class,
    ];
  }

  $variables['control'] = $control;

  $variables['is_inline'] = isset($element['#options_display']) && ($element['#options_display'] == 'inline' || $element['#options_display'] == 'buttons_horizontal');

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
  $variables['type'] = isset($element['#type']) ? $element['#type'] : 'input';

  $variables['title'] = isset($element['#title']) ? $element['#title'] : '';

  $title_display = $element['#_title_display'] ?? $element['#title_display'] ?? $variables['title_display'] ?? 'visible';
  $title_display = $title_display == 'none' ? 'hidden' : $title_display;
  $title_display = $title_display == 'visually_hidden' ? 'invisible' : $title_display;
  if (empty($variables['title'])) {
    $title_display = 'hidden';
  }
  $variables['title_display'] = $title_display;

  $variables['orientation'] = $variables['orientation'] ?? $title_display == 'inline' ? 'horizontal' : 'vertical';

  $variables['is_required'] = isset($element['#required']) ? $element['#required'] : FALSE;
  $variables['is_disabled'] = isset($element['#attributes']['disabled']);
  $variables['description'] = isset($element['#description']) ? $element['#description'] : '';
  $variables['message'] = isset($element['#errors']) ? $element['#errors'] : '';
  $variables['is_invalid'] = isset($element['#errors']) && !empty($element['#errors']);
  $variables['placeholder'] = isset($element['#placeholder']) ? $element['#placeholder'] : '';
}

/**
 * Preprocesses a form element control.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
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
  $attributes_string = implode(' ', array_map(function ($key, $value) {
    return $key . '="' . $value . '"';
  }, array_keys($attributes), $attributes));

  $modifier_class = isset($element['#attributes']['class']) ? $element['#attributes']['class'] : [];
  if (is_array($modifier_class)) {
    $modifier_class = implode(' ', $modifier_class);
  }

  $variables['control'][] = [
    'name' => isset($element['#attributes']['name']) ? $element['#attributes']['name'] : (isset($element['#name']) ? $element['#name'] : ''),
    'value' => isset($element['#value']) ? $element['#value'] : '',
    'id' => isset($element['#id']) ? $element['#id'] : '',
    'options' => $variables['options'] ?? [],
    'is_multiple' => isset($variables['is_multiple']) ? $variables['is_multiple'] : FALSE,
    'attributes' => $attributes_string,
    'modifier_class' => $modifier_class,
  ];

  if ($element['#type'] == 'checkbox') {
    $variables['control'][0]['is_checked'] = isset($element['#default_value']) ? $element['#default_value'] : FALSE;
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
  elseif (!empty($variables['element']['#name'])) {
    $variables['attributes']['class'][] = 'js-form-item-' . Html::getClass($variables['element']['#name']);
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
function _civictheme_preprocess_form_element__wrapper_classes(array &$variables){
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
