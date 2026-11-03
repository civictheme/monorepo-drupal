<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Theme\Form;

use Drupal\civictheme_starter\CivicthemeConstants;
use Drupal\Core\Render\Element;

/**
 * Shared form element helpers.
 *
 * Internal to CivicTheme: shared by the form, views and webform
 * preprocessors.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
final class FormElementHelper {

  /**
   * Check whether element is a webform element.
   *
   * Inlined to avoid a hard dependency on the webform module's classes, and
   * narrowed to the element-level markers: unlike the upstream helper, being
   * on a webform admin route does not make every element on the page a
   * webform element.
   *
   * @see \Drupal\webform\Utility\WebformElementHelper::isWebformElement()
   */
  public static function isWebformElement(array $element): bool {
    if (!\Drupal::service('module_handler')->moduleExists('webform')) {
      return FALSE;
    }

    return isset($element['#webform_key']) || isset($element['#webform_element']);
  }

  /**
   * Get from elements the form without hidden fields.
   *
   * @param array $form
   *   Form array.
   *
   * @return array<string, mixed>
   *   Array of fields.
   */
  public static function getFormElements(array $form): array {
    $exclude = [
      'form_build_id',
      'form_id',
      'form_token',
      'actions',
    ];

    $field_names = Element::children($form);

    return array_intersect_key($form, array_flip(array_diff($field_names, $exclude)));
  }

  /**
   * Check if form element is set to have multiple values.
   */
  public static function isMultiple(array $element): bool {
    return $element['#type'] === 'checkboxes' || ($element['#type'] === 'select' && ($element["#multiple"] ?? FALSE));
  }

  /**
   * Preprocess hidden form elements.
   */
  public static function preprocessHiddenFormElements(array &$variables): void {
    if (empty($variables['form'])) {
      return;
    }

    $form = &$variables['form'];

    $field_names = [
      'form_build_id',
      'form_id',
      'form_token',
    ];

    $output = [];
    foreach (Element::children($form) as $field_name) {
      if (in_array($field_name, $field_names)) {
        $output[] = $form[$field_name];
      }
    }

    $variables['form_hidden_fields'] = $output;
  }

  /**
   * Get component theme from the form element.
   */
  public static function getComponentTheme(array $element): string {
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
   * Propagate component theme setting to nested form elements.
   */
  public static function propagateComponentTheme(array &$elements, string $theme): void {
    $form_elements = Element::children($elements);
    foreach ($form_elements as $form_element) {
      $elements[$form_element]['#attributes']['#civictheme_theme'] = $theme;
      $elements[$form_element]['#attributes']['#component_theme'] = $theme;
      $children = Element::children($elements[$form_element]);
      if (!empty($children)) {
        self::propagateComponentTheme($elements[$form_element], $theme);
      }
    }
  }

}
