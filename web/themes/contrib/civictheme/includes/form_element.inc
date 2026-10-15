<?php

/**
 * @file
 * Form elements.
 *
 * CivicTheme renders form elements through 1:1 overrides of core form
 * templates (the Claro/Olivero pattern): core prepares every variable and
 * renders the control; the theme adds classes and small variables via thin
 * preprocess functions and maps the prepared variables onto CivicTheme
 * components in the template overrides. Controls are never rebuilt from the
 * render array, so core and contrib behaviour (ids, states, selectors,
 * checked state, ARIA) always survives.
 *
 * The render pipeline:
 *
 * - form_element: form-element.html.twig passes the core-rendered control
 *   through the CivicTheme Field component's control slot. Per-type
 *   adjustments use the universal form_element__TYPE suggestions
 *   (form-element--checkbox.html.twig and form-element--radio.html.twig
 *   render the label after the control).
 * - input, select, textarea: thin preprocess adds the CivicTheme control
 *   classes and validation ARIA attributes to the core-rendered control.
 * - checkboxes, radios: checkboxes.html.twig and radios.html.twig render the
 *   group as a Field-style fieldset around the core-rendered options, while
 *   fieldset--checkboxes.html.twig and fieldset--radios.html.twig reduce the
 *   core composite wrapper to a plain container.
 * - input__submit: input--submit.html.twig renders the Button component.
 *
 * Sub-themes override a single element type by providing the
 * form-element--TYPE.html.twig (or input--TYPE.html.twig) template - no PHP
 * is required.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;
use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_theme_suggestions_form_element_alter().
 */
function civictheme_theme_suggestions_form_element_alter(array &$suggestions, array $variables): void {
  // Provide a suggestion per element type so that element types can be
  // overridden with a single template (core itself provides none).
  if (!empty($variables['element']['#type'])) {
    $suggestions[] = 'form_element__' . $variables['element']['#type'];
  }
}

/**
 * Implements hook_theme_suggestions_fieldset_alter().
 */
function civictheme_theme_suggestions_fieldset_alter(array &$suggestions, array $variables): void {
  // Checkboxes and radios groups render their own fieldset structure in
  // checkboxes.html.twig / radios.html.twig, so the core composite fieldset
  // wrapper around them is reduced to a plain container.
  $group_types = ['checkboxes', 'radios'];
  if (isset($variables['element']['#type']) && in_array($variables['element']['#type'], $group_types)) {
    $suggestions[] = 'fieldset__' . $variables['element']['#type'];
  }
}

/**
 * Implements template_preprocess_form_element().
 */
function civictheme_preprocess_form_element(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_preprocess_form_element__generic($variables);
  _civictheme_preprocess_form_element__classes($variables);
  _civictheme_preprocess_form_element__description($variables);

  // The checkbox/radio form element templates render the Field structure
  // without rendering the component, so its library is not auto-attached.
  $variables['#attached']['library'][] = 'core/components.civictheme--field';
}

/**
 * Implements hook_preprocess_HOOK() for form_element__datetime.
 *
 * Prepares variables for the accessible datetime composite wrapper so it is
 * rendered through the CivicTheme Fieldset component, grouping the inner
 * date and time inputs inside a <fieldset>/<legend>.
 */
function civictheme_preprocess_form_element__datetime(array &$variables): void {
  $element = $variables['element'];

  $classes = $variables['attributes']['class'] ?? [];
  if (is_string($classes)) {
    $classes = [$classes];
  }
  $classes[] = 'ct-field';
  $variables['attributes']['class'] = $classes;

  $variables['legend_title'] = (string) ($element['#title'] ?? '');
  $variables['is_required'] = !empty($element['#required']);
  $variables['description'] = $element['#description'] ?? '';
}

/**
 * Implements hook_preprocess_HOOK() for datetime_form.
 *
 * Replaces the inner datetime_form content with a CivicTheme Grid component
 * that lays out the date and time sub-elements side-by-side.
 */
function civictheme_preprocess_datetime_form(array &$variables): void {
  $element = $variables['element'];
  if (empty($element['date']) || empty($element['time'])) {
    return;
  }

  $variables['type'] = 'datetime';
  if (!empty($element['#name'])) {
    $variables['name'] = $element['#name'];
  }
  _civictheme_preprocess_form_element__classes($variables);

  $grid_attributes = new Attribute($variables['attributes'] ?? []);
  $variables['attributes'] = [];

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:grid',
    '#props' => [
      'template_column_count' => 2,
      'use_container' => FALSE,
      'attributes' => $grid_attributes,
      'items' => [
        $element['date'],
        $element['time'],
      ],
    ],
  ];
}

/**
 * Implements template_preprocess_checkboxes().
 */
function civictheme_preprocess_checkboxes(array &$variables): void {
  _civictheme_preprocess_form_element__group($variables, 'checkbox');
}

/**
 * Implements template_preprocess_radios().
 */
function civictheme_preprocess_radios(array &$variables): void {
  _civictheme_preprocess_form_element__group($variables, 'radio');
}

/**
 * Implements template_preprocess_fieldset() for fieldset__checkboxes.
 */
function civictheme_preprocess_fieldset__checkboxes(array &$variables): void {
  // The group renders its own structure in checkboxes.html.twig - keep the
  // composite wrapper attributes (states wiring and ids) on the plain
  // container by skipping the attributes-to-modifier_class conversion.
  $variables['modifier_class'] = FALSE;
}

/**
 * Implements template_preprocess_fieldset() for fieldset__radios.
 */
function civictheme_preprocess_fieldset__radios(array &$variables): void {
  $variables['modifier_class'] = FALSE;
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
function civictheme_preprocess_input(array &$variables): void {
  $element = $variables['element'];
  $type = $element['#type'] ?? 'input';

  // Buttons render through the Button component in input--submit.html.twig
  // and image buttons keep the core rendering.
  if (in_array($type, ['submit', 'button', 'reset', 'image_button'])) {
    return;
  }

  _civictheme_preprocess_form_element__theme($variables);

  $kind = match ($type) {
    'checkbox' => 'checkbox',
    'radio' => 'radio',
    'textfield' => 'textfield',
    default => 'input',
  };

  _civictheme_form_control_attributes($variables, $kind);
}

/**
 * Implements template_preprocess_select().
 */
function civictheme_preprocess_select(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_form_control_attributes($variables, 'select');
}

/**
 * Implements template_preprocess_textarea().
 */
function civictheme_preprocess_textarea(array &$variables): void {
  _civictheme_preprocess_form_element__theme($variables);
  _civictheme_form_control_attributes($variables, 'textarea');
}

/**
 * Implements template_preprocess_input() for input__submit.
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
 * Implements template_preprocess_form_element_label().
 */
function civictheme_preprocess_form_element_label(array &$variables): void {
  $variables['content'] = $variables['title'] ?? '';
  $variables['tag'] = 'label';
  $variables['size'] = 'regular';
  $variables['is_required'] = $variables['required'] ?? FALSE;
  $variables['required_text'] = $variables['is_required'] ? (string) t('(required)') : '';
  $variables['for'] = $variables['attributes']['for'] ?? '';
  $variables['theme'] = $variables['theme'] ?? 'light';
  $variables['modifier_class'] = $variables['modifier_class'] ?? '';
  if (in_array($variables['title_display'] ?? '', ['invisible', 'visually_hidden'], TRUE)) {
    $variables['modifier_class'] = trim($variables['modifier_class'] . ' ct-visually-hidden');
  }
  unset($variables['attributes']['for']);
}

/**
 * Preprocesses a generic form element.
 *
 * Maps the core-prepared form element variables onto the CivicTheme Field
 * component props. The control itself stays untouched - it is rendered by
 * core into 'children' and passed through the component's control slot.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_preprocess_form_element__generic(array &$variables): void {
  $element = $variables['element'];
  $variables['type'] = _civictheme_form_element_presentation_type($element['#type'] ?? 'input');

  $variables['title'] = (string) ($element['#title'] ?? '');

  $raw_title_display = (string) ($element['#_title_display'] ?? $element['#title_display'] ?? $variables['title_display'] ?? 'visible');

  // An inline title renders the field horizontally.
  $variables['orientation'] = $raw_title_display === 'inline' ? 'horizontal' : 'vertical';

  $title_display = $raw_title_display === 'none' ? 'hidden' : $raw_title_display;
  $title_display = $title_display === 'visually_hidden' ? 'invisible' : $title_display;
  // Drupal uses 'before'/'after'/'inline' for the label position; map to the
  // component's display enum.
  if (in_array($title_display, ['before', 'after', 'inline'], TRUE)) {
    $title_display = 'visible';
  }
  if (empty($variables['title'])) {
    $title_display = 'hidden';
  }
  $variables['title_display'] = $title_display;
  $title_size = $element['#title_size'] ?? '';
  $variables['title_size'] = $title_size !== '' ? $title_size : 'regular';

  // The label references the control via its id; #label_for allows elements
  // (such as managed_file) to point the label at an inner control instead.
  $variables['control_id'] = (string) (!empty($element['#label_for']) ? $element['#label_for'] : ($element['#id'] ?? ''));
  $variables['is_required'] = !empty($element['#required']);
  $variables['is_disabled'] = isset($element['#attributes']['disabled']);
  $variables['required_text'] = $variables['is_required'] ? (string) t('(required)') : '';

  $variables['is_invalid'] = !empty($element['#errors']);
  $variables['message'] = $variables['message'] ?? ['content' => ''];
  if ($variables['is_invalid']) {
    $variables['message']['content'] = $element['#errors'];
    $element_id = $element['#id'] ?? $element['#attributes']['data-drupal-selector'] ?? '';
    if (!empty($element_id)) {
      $variables['message']['attributes'] = new Attribute(['id' => $element_id . '--error-message']);
    }
  }

  $prefix = $element['#field_prefix'] ?? '';
  $variables['prefix'] = !empty($prefix) ? [
    '#type' => 'markup',
    '#markup' => $prefix,
  ] : '';
  $suffix = $element['#field_suffix'] ?? '';
  $variables['suffix'] = !empty($suffix) ? [
    '#type' => 'markup',
    '#markup' => $suffix,
  ] : '';
}

/**
 * Preprocesses a checkboxes/radios group.
 *
 * The group template renders a Field-style fieldset structure around the
 * core-rendered options passed in 'children'.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function _civictheme_preprocess_form_element__group(array &$variables, string $type): void {
  $element = $variables['element'];

  _civictheme_preprocess_form_element__theme($variables);

  // Apply wrapper attributes to the group wrapper, keeping the attributes
  // (such as the group id) prepared by core.
  if (!empty($element['#wrapper_attributes'])) {
    $wrapper_attributes = $element['#wrapper_attributes'];
    $wrapper_classes = (array) ($wrapper_attributes['class'] ?? []);
    unset($wrapper_attributes['class']);
    $variables['attributes'] = $wrapper_attributes + $variables['attributes'];
    $variables['attributes']['class'] = array_merge((array) ($variables['attributes']['class'] ?? []), $wrapper_classes);
  }

  $variables['type'] = $type;
  $variables['title'] = (string) ($element['#title'] ?? '');

  $title_display = (string) ($element['#title_display'] ?? 'visible');
  $title_display = in_array($title_display, ['none', 'hidden'], TRUE) ? 'hidden' : $title_display;
  $title_display = in_array($title_display, ['visually_hidden', 'invisible'], TRUE) ? 'invisible' : $title_display;
  $title_display = in_array($title_display, ['hidden', 'invisible'], TRUE) ? $title_display : 'visible';
  if (empty($variables['title'])) {
    $title_display = 'hidden';
  }
  $variables['title_display'] = $title_display;

  $variables['description'] = $element['#description'] ?? '';
  $variables['is_required'] = !empty($element['#required']);
  $variables['required_text'] = $variables['is_required'] ? (string) t('(required)') : '';
  $variables['is_disabled'] = isset($element['#attributes']['disabled']);
  $variables['is_invalid'] = !empty($element['#errors']);
  $options_display = $element['#options_display'] ?? '';
  $variables['orientation'] = in_array($options_display, ['inline', 'buttons_horizontal']) ? 'horizontal' : 'vertical';
  $variables['name'] = $element['#name'] ?? '';

  $variables['message'] = ['content' => ''];
  $element_id = $element['#id'] ?? '';
  if ($variables['is_invalid']) {
    $variables['message']['content'] = $element['#errors'];
    if (!empty($element_id)) {
      $variables['message']['attributes'] = new Attribute(['id' => $element_id . '--error-message']);
    }
  }

  $variables['description_id'] = !empty($element_id) && !empty($variables['description']) ? $element_id . '--description' : '';

  _civictheme_preprocess_form_element__classes($variables);

  $variables['#attached']['library'][] = 'core/components.civictheme--field';
}

/**
 * Adds CivicTheme control classes and validation attributes to a control.
 *
 * Applies to controls rendered by the core input, select and textarea
 * templates. The classes stay in the element attributes (the conversion to
 * modifier_class is skipped) because core templates print attributes
 * directly.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function _civictheme_form_control_attributes(array &$variables, string $kind): void {
  $element = $variables['element'];
  $type = $element['#type'] ?? 'input';
  $theme = $variables['theme'] ?? 'light';

  $classes = [
    'ct-' . $kind,
    'ct-theme-' . $theme,
    'ct-field__control',
    'form-element',
    'form-element--type-' . $type,
    'form-element--api-' . $type,
    'form-control',
  ];

  if (in_array($type, ['checkbox', 'radio'])) {
    $classes[] = 'form-boolean';
    $classes[] = Html::getClass('form-boolean--type-' . $type);
  }

  $attributes = [];
  if (!empty($element['#errors'])) {
    $classes[] = 'ct-' . $kind . '--is-invalid';
    $attributes['aria-invalid'] = 'true';
    if (!empty($element['#id'])) {
      $attributes['aria-describedby'] = $element['#id'] . '--error-message';
    }
  }

  if (!$variables['attributes'] instanceof Attribute) {
    $variables['attributes'] = new Attribute($variables['attributes'] ?? []);
  }

  $variables['attributes']->addClass($classes);
  foreach ($attributes as $name => $value) {
    if (!$variables['attributes']->hasAttribute($name)) {
      $variables['attributes']->setAttribute($name, $value);
    }
  }
  $variables['attributes']->removeAttribute('#civictheme_theme', '#component_theme');

  // The control markup is rendered by core templates, not by the component,
  // so the component's auto-generated library must be attached explicitly.
  $variables['#attached']['library'][] = 'core/components.civictheme--' . $kind;

  // Keep the classes on the control: core templates print attributes
  // directly, so skip the global attributes-to-modifier_class conversion.
  $variables['modifier_class'] = FALSE;
}

/**
 * Maps an element API type to the presentation type used for classes.
 */
function _civictheme_form_element_presentation_type(string $type): string {
  $map = [
    'webform_time' => 'time',
    'webform_date' => 'date',
  ];

  return $map[$type] ?? $type;
}

/**
 * Allows override of CivicTheme component settings from webform settings.
 *
 * These are set via customer settings (yaml) in webform elements.
 *
 * @see https://www.drupal.org/project/civictheme/issues/3581086
 */
function _civictheme_preprocess_form_element__webform__overrides(array &$variables): void {
  $element = $variables['element'];
  if (!civictheme_is_webform_element($element)) {
    return;
  }

  $overrideable_settings = [
    'theme',
    'is_contained',
    'vertical_spacing',
    'with_background',
    'modifier_class',
  ];
  foreach ($overrideable_settings as $setting) {
    $webform_setting_id = '#' . $setting;
    if ($setting === 'theme') {
      $webform_setting_id = '#component_theme';
    }
    if (isset($element[$webform_setting_id])) {
      $variables[$setting] = $element[$webform_setting_id];
    }
  }
  if (!empty($variables['children'])) {
    $variables['content'] = $variables['children'];
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

  // Default theme to 'light' when not set.
  if (empty($variables['theme'])) {
    $variables['theme'] = 'light';
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
  if (!civictheme_is_webform_element($variables['element'] ?? [])) {
    return;
  }
  $variables['description'] = $variables['description'] ?? $variables['element']['#help_title'] ?? NULL;
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
  return $element['#type'] === 'checkboxes' || ($element['#type'] === 'select' && ($element["#multiple"] ?? FALSE));
}
