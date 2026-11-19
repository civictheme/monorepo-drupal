<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook\Preprocess;

use Drupal\civictheme\CivicthemeConstants;
use Drupal\civictheme\Theme\Form\FormElementHelper;
use Drupal\civictheme\Utility\CivicthemeAttribute;
use Drupal\civictheme\Utility\CivicthemeOptions;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Template\Attribute;

/**
 * Form element preprocess hook implementations for CivicTheme.
 *
 * CivicTheme renders form elements through 1:1 overrides of core form
 * templates (the Claro/Olivero pattern): core prepares every variable, and
 * the template overrides render the CivicTheme components from those
 * variables - the control atoms (input, select, textarea, checkbox, radio)
 * for the controls, the Field component for the element wrapper - with thin
 * preprocess methods contributing classes and small variables. Controls
 * are never rebuilt from the render array, so core and contrib behaviour
 * (ids, states, selectors, checked state, ARIA) always survives.
 *
 * The render pipeline:
 *
 * - form_element: form-element.html.twig passes the rendered control through
 *   the CivicTheme Field component's control slot. The component understands
 *   the core title_display vocabulary directly (including the 'after' label
 *   layout of single checkboxes and radios); per-type adjustments hook into
 *   the universal form_element__TYPE suggestions.
 * - input, select, textarea: the template overrides render the matching
 *   CivicTheme control atom from core's prepared attributes; thin preprocess
 *   contributes the Drupal control classes and validation ARIA attributes.
 * - checkboxes, radios: the core composite fieldset wrapper around the group
 *   renders through the CivicTheme Field component in
 *   fieldset--checkboxes.html.twig and fieldset--radios.html.twig, mapped
 *   from the variables core's fieldset preprocessing prepares (the Claro
 *   pattern); the Field root is the <fieldset> (is_group) and the rendered
 *   options pass through the control slot.
 * - input__submit: input--submit.html.twig renders the Button component.
 *
 * Sub-themes override a single element type by providing the
 * form-element--TYPE.html.twig (or input--TYPE.html.twig) template - no PHP
 * is required.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 */
class FormHooks {

  use StringTranslationTrait;

  /**
   * Implements template_preprocess_form_element().
   */
  #[Hook('preprocess_form_element')]
  public function preprocessFormElement(array &$variables): void {
    $this->setElementTheme($variables);
    $this->preprocessGeneric($variables);
    $this->relocateLabelHelp($variables);
    $this->preprocessClasses($variables);
    $this->preprocessDescription($variables);
  }

  /**
   * Implements hook_preprocess_HOOK() for form_element__item.
   *
   * Webform computed elements (webform_computed_token, webform_computed_twig)
   * convert themselves to #type 'item' during processing so #states keep
   * working; their #template key identifies them among item elements.
   */
  #[Hook('preprocess_form_element__item')]
  public function preprocessFormElementItem(array &$variables): void {
    if (isset($variables['element']['#template'])) {
      $this->preprocessDisplayContent($variables);
    }
  }

  /**
   * Implements hook_preprocess_HOOK() for form_element__datetime.
   *
   * Prepares variables for the accessible datetime composite wrapper so it
   * is rendered through the CivicTheme Fieldset component, grouping the
   * inner date and time inputs inside a <fieldset>/<legend>.
   */
  #[Hook('preprocess_form_element__datetime')]
  public function preprocessFormElementDatetime(array &$variables): void {
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
   * Replaces the inner datetime_form content with a CivicTheme Grid
   * component that lays out the date and time sub-elements side-by-side.
   */
  #[Hook('preprocess_datetime_form')]
  public function preprocessDatetimeForm(array &$variables): void {
    $element = $variables['element'];
    if (empty($element['date']) || empty($element['time'])) {
      return;
    }

    $variables['type'] = 'datetime';
    if (!empty($element['#name'])) {
      $variables['name'] = $element['#name'];
    }
    $this->preprocessClasses($variables);

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
   * Implements template_preprocess_fieldset() for fieldset__checkboxes.
   */
  #[Hook('preprocess_fieldset__checkboxes')]
  public function preprocessFieldsetCheckboxes(array &$variables): void {
    $this->preprocessFieldsetGroup($variables, 'checkbox');
  }

  /**
   * Implements template_preprocess_fieldset() for fieldset__radios.
   */
  #[Hook('preprocess_fieldset__radios')]
  public function preprocessFieldsetRadios(array &$variables): void {
    $this->preprocessFieldsetGroup($variables, 'radio');
  }

  /**
   * Implements template_preprocess_fieldset().
   */
  #[Hook('preprocess_fieldset')]
  public function preprocessFieldset(array &$variables): void {
    $element = $variables['element'];

    if ($element['#type'] !== 'fieldset') {
      return;
    }

    $this->setElementTheme($variables);
    $this->preprocessClasses($variables);
    $this->preprocessDescription($variables);
  }

  /**
   * Implements template_preprocess_input().
   */
  #[Hook('preprocess_input')]
  public function preprocessInput(array &$variables): void {
    $element = $variables['element'];
    $type = $element['#type'] ?? 'input';

    // Buttons render through the Button component in input--submit.html.twig
    // and image buttons keep the core rendering.
    if (in_array($type, ['submit', 'button', 'reset', 'image_button'])) {
      return;
    }

    $this->setElementTheme($variables);

    $kind = match ($type) {
      'checkbox' => 'checkbox',
      'radio' => 'radio',
      'textfield' => 'textfield',
      default => 'input',
    };

    $this->prepareControl($variables, $kind);
  }

  /**
   * Implements template_preprocess_input() for input__submit.
   */
  #[Hook('preprocess_input__submit')]
  public function preprocessInputSubmit(array &$variables): void {
    $this->setElementTheme($variables);

    $type = 'primary';
    if (is_array($variables['attributes']['class']) && in_array('secondary', $variables['attributes']['class'])) {
      $type = 'secondary';
    }
    $variables['type'] = $type;

    $variables['value'] = $variables['attributes']['value'] ?? $this->t('Submit');
  }

  /**
   * Implements template_preprocess_select().
   */
  #[Hook('preprocess_select')]
  public function preprocessSelect(array &$variables): void {
    $this->setElementTheme($variables);
    $this->prepareControl($variables, 'select');
  }

  /**
   * Implements template_preprocess_textarea().
   */
  #[Hook('preprocess_textarea')]
  public function preprocessTextarea(array &$variables): void {
    $this->setElementTheme($variables);
    $this->prepareControl($variables, 'textarea');
  }

  /**
   * Implements template_preprocess_form_element_label().
   */
  #[Hook('preprocess_form_element_label')]
  public function preprocessFormElementLabel(array &$variables): void {
    $variables['content'] = $variables['title'] ?? '';
    $variables['tag'] = 'label';
    $variables['size'] = 'regular';
    $variables['is_required'] = $variables['required'] ?? FALSE;
    $variables['required_text'] = $variables['is_required'] ? (string) $this->t('(required)') : '';
    $variables['for'] = $variables['attributes']['for'] ?? '';
    $variables['theme'] = $variables['theme'] ?? 'light';
    $variables['modifier_class'] = $variables['modifier_class'] ?? '';
    if (in_array($variables['title_display'] ?? '', ['invisible', 'visually_hidden'], TRUE)) {
      $variables['modifier_class'] = trim($variables['modifier_class'] . ' ct-visually-hidden');
    }
    unset($variables['attributes']['for']);
  }

  /**
   * Sets form element theme based on attributes passed from parents.
   */
  protected function setElementTheme(array &$variables): void {
    // Setting theme for form element.
    if (
      isset($variables['element']['#attributes']['#civictheme_theme'])
      && in_array($variables['element']['#attributes']['#civictheme_theme'], CivicthemeOptions::themeOptions(TRUE))
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
   * Preprocesses a generic form element.
   *
   * Maps the core-prepared form element variables onto the CivicTheme Field
   * component props. The control itself stays untouched - it is rendered by
   * core into 'children' and passed through the component's control slot.
   *
   * @SuppressWarnings("PHPMD.CyclomaticComplexity")
   * @SuppressWarnings("PHPMD.NPathComplexity")
   */
  protected function preprocessGeneric(array &$variables): void {
    $element = $variables['element'];
    $variables['type'] = $this->presentationType($element['#type'] ?? 'input');

    $variables['title'] = (string) ($element['#title'] ?? '');

    // The Field component accepts and normalises the core title_display
    // vocabulary itself: 'after' renders the label after the control and
    // 'inline' renders the field horizontally.
    $variables['title_display'] = (string) ($element['#_title_display'] ?? $element['#title_display'] ?? $variables['title_display'] ?? 'visible');
    $title_size = $element['#title_size'] ?? '';
    $variables['title_size'] = $title_size !== '' ? $title_size : 'regular';

    // The label references the control via its id; #label_for allows
    // elements (such as managed_file) to point the label at an inner control
    // instead.
    $variables['control_id'] = (string) (empty($element['#label_for']) ? $element['#id'] ?? '' : ($element['#label_for']));
    $variables['is_required'] = !empty($element['#required']);
    $variables['is_disabled'] = isset($element['#attributes']['disabled']);
    $variables['required_text'] = $variables['is_required'] ? (string) $this->t('(required)') : '';

    // The Field component derives the message id ({id}--error-message) from
    // the control id, matching the aria-describedby reference on the
    // control.
    $variables['is_invalid'] = !empty($element['#errors']);
    $variables['message'] = $variables['message'] ?? ['content' => ''];
    if ($variables['is_invalid']) {
      $variables['message']['content'] = $element['#errors'];
    }

    // Core maps #field_prefix/#field_suffix onto prefix/suffix; module
    // preprocess may replace them with builds (webform renders element-level
    // #help through them) - flatten builds to markup for the component
    // props.
    $variables['prefix'] = $this->flattenMarkup($variables['prefix'] ?? $element['#field_prefix'] ?? '');
    $variables['suffix'] = $this->flattenMarkup($variables['suffix'] ?? $element['#field_suffix'] ?? '');
  }

  /**
   * Prepares the composite fieldset wrapper of a checkboxes/radios group.
   *
   * Core's fieldset preprocessing prepares the legend, description, required
   * state and the rendered options; the suggestion template maps them onto
   * the CivicTheme Field component. This helper contributes the theme, the
   * presentation type and the Drupal compat classes.
   */
  protected function preprocessFieldsetGroup(array &$variables, string $type): void {
    $this->setElementTheme($variables);

    $variables['type'] = $type;
    $variables['name'] = $variables['element']['#name'] ?? '';

    // Webform's composite pre-render merges #wrapper_attributes into the
    // fieldset attributes; core's keeps the element's own #attributes -
    // which its process callback also copies onto every child control - and
    // drops #wrapper_attributes. Rebuild the core-prepared wrapper
    // attributes the way form elements get them: the element's wrapper
    // attributes plus the wrapper id and classes core's composite pre-render
    // contributes, keeping the control attributes (classes, data attributes,
    // the required marker) on the controls only.
    $element = $variables['element'];
    if (!FormElementHelper::isWebformElement($element)) {
      $attributes = $element['#wrapper_attributes'] ?? [];
      if (empty($attributes['id']) && !empty($variables['attributes']['id'])) {
        $attributes['id'] = $variables['attributes']['id'];
      }
      $attributes['class'] = array_merge((array) ($attributes['class'] ?? []), ['fieldgroup', 'form-composite']);
      $variables['attributes'] = $attributes;
    }

    $this->preprocessClasses($variables);

    // Webform rebuilds the group description content as a nested build and
    // appends the #help build to the legend title: flatten the description
    // for the Field component and relocate the legend help next to the
    // control.
    $description = $this->flattenDescription($variables['description']['content'] ?? NULL);
    if ($description !== NULL) {
      $variables['description']['content'] = $description;
    }
    $this->relocateHelp($variables, $variables['legend']['title'] ?? NULL);

    // The Field component renders the description with an id derived from
    // the group id, so the core-added reference to the wrapper-id-based
    // description would dangle; the theme keys must not print as attributes.
    unset($variables['attributes']['aria-describedby'], $variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
  }

  /**
   * Wraps a display element's rendered content in Basic Content.
   *
   * Computed elements render display markup, not a control, through the
   * form_element pipeline: the Field wrapper keeps the title, description
   * and required state, and the markup passing through the control slot
   * gains rich-text typography.
   */
  protected function preprocessDisplayContent(array &$variables): void {
    if (empty($variables['children'])) {
      return;
    }
    $variables['children'] = [
      '#type' => 'component',
      '#component' => 'civictheme:basic-content',
      '#props' => [
        'theme' => $variables['theme'] ?? CivicthemeConstants::THEME_LIGHT,
        'is_contained' => FALSE,
      ],
      '#slots' => [
        'content' => [
          '#markup' => $variables['children'],
        ],
      ],
    ];
  }

  /**
   * Relocates webform's title-level help tooltip out of the label.
   *
   * Webform appends the #help build into the label render array; the Field
   * component renders the title from the element, so the help would be lost.
   */
  protected function relocateLabelHelp(array &$variables): void {
    $this->relocateHelp($variables, $variables['label']['#title'] ?? NULL);
  }

  /**
   * Relocates a webform #help build from a title build into the prefix.
   *
   * A tooltip button inside the <label> or <legend> would nest interactive
   * elements, so the help renders between the title block and the control
   * instead, through the Field prefix.
   */
  protected function relocateHelp(array &$variables, mixed $title): void {
    $help = is_array($title) ? ($title['help'] ?? NULL) : NULL;
    if (!is_array($help)) {
      return;
    }
    $prefix = $this->flattenMarkup($variables['prefix'] ?? '');
    if (!$prefix instanceof MarkupInterface) {
      $prefix = Html::escape((string) $prefix);
    }
    $variables['prefix'] = Markup::create(\Drupal::service('renderer')->render($help) . $prefix);
  }

  /**
   * Flattens a preprocess variable to markup for a string component prop.
   */
  protected function flattenMarkup(mixed $value): string|\Stringable {
    if (is_array($value)) {
      return \Drupal::service('renderer')->render($value);
    }

    return $value instanceof \Stringable ? $value : (string) ($value ?? '');
  }

  /**
   * Flattens a module-rebuilt description content build to markup.
   *
   * Webform rebuilds the description content as a nested build carrying the
   * visually-hidden handling and the (read) more disclosure. The Field
   * component renders its own description wrapper with the
   * "{id}--description" id the control references, so the id webform moved
   * onto the inner container is dropped before rendering to avoid duplicate
   * ids.
   */
  protected function flattenDescription(mixed $content): string|\Stringable|NULL {
    if (!is_array($content)) {
      return NULL;
    }
    if (isset($content['description']['#attributes']) && $content['description']['#attributes'] instanceof Attribute) {
      $content['description']['#attributes']->removeAttribute('id');
    }

    return $this->flattenMarkup($content);
  }

  /**
   * Prepares a control for rendering through its CivicTheme component.
   *
   * The control templates (input.html.twig, select.html.twig,
   * textarea.html.twig) include the matching component, passing core's
   * prepared attributes through. This helper contributes the Drupal-specific
   * classes (the component adds its own ct-* classes), the validation state
   * and the error message reference, and tells the template which component
   * to use.
   */
  protected function prepareControl(array &$variables, string $kind): void {
    $element = $variables['element'];
    $type = $element['#type'] ?? 'input';

    $variables['civictheme_kind'] = $kind;
    $variables['is_invalid'] = !empty($element['#errors']);

    $classes = [
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
    if ($variables['is_invalid'] && !empty($element['#id'])) {
      $attributes['aria-describedby'] = $element['#id'] . '--error-message';
    }

    if ($variables['attributes'] instanceof Attribute) {
      // Move the classes into modifier_class for the component, like the
      // global attributes-to-modifier_class conversion does for array
      // attributes in CivicthemeThemeHooks::preprocessLast().
      $existing = (array) $variables['attributes']->getClass()->value();
      $variables['attributes']->removeAttribute('class');
      CivicthemeAttribute::addModifierClass($variables, array_merge($existing, $classes));
      foreach ($attributes as $name => $value) {
        if (!$variables['attributes']->hasAttribute($name)) {
          $variables['attributes']->setAttribute($name, $value);
        }
      }
      $variables['attributes']->removeAttribute('#civictheme_theme', '#component_theme');
      return;
    }

    $existing = $variables['attributes']['class'] ?? [];
    $existing = is_string($existing) ? [$existing] : $existing;
    $variables['attributes']['class'] = array_merge($existing, $classes);
    $variables['attributes'] += $attributes;
    unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
  }

  /**
   * Maps an element API type to the presentation type used for classes.
   */
  protected function presentationType(string $type): string {
    $map = [
      'webform_time' => 'time',
      'webform_date' => 'date',
    ];

    return $map[$type] ?? $type;
  }

  /**
   * Preprocesses form element classes.
   */
  protected function preprocessClasses(array &$variables): void {
    $variables['attributes']['class'] = $variables['attributes']['class'] ?? [];
    $variables['attributes']['class'] = is_string($variables['attributes']['class']) ? [$variables['attributes']['class']] : $variables['attributes']['class'];

    // Add missing core Drupal form element classes that are added in
    // template file.
    // Note that we are only adding JS classes for 3rd-parties to bind to.
    // Any other classes added by core or contrib modules may affect the look
    // and feel of the form.
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
  protected function preprocessDescription(array &$variables): void {
    // Module preprocess may rebuild the description content as a nested
    // build: webform adds the visually-hidden handling and the (read) more
    // disclosure this way. Render such a build; other elements keep the raw
    // description.
    $description = $this->flattenDescription($variables['description']['content'] ?? NULL);
    $variables['description'] = $description ?? $variables['element']['#description'] ?? NULL;
    $variables['description_display'] = $variables['element']['#description_display'] ?? NULL;
  }

}
