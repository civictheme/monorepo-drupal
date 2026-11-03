<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Hook\Preprocess;

use Drupal\civictheme_starter\Theme\Form\FormElementHelper;
use Drupal\civictheme_starter\Theme\HtmlContentProcessor;
use Drupal\civictheme_starter\Utility\CivicthemeOptions;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Template\Attribute;

/**
 * Webform preprocess hook implementations for CivicTheme.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class CivicThemeStarterWebformHooks {

  /**
   * Implements template_preprocess_webform().
   *
   * The webform theme wrapper renders the form tag: theme it from the keys
   * the webform submission form alter sets on the form root.
   */
  #[Hook('preprocess_webform')]
  public function preprocessWebform(array &$variables): void {
    $variables['theme'] = FormElementHelper::getComponentTheme($variables['element'] ?? []);
    unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
  }

  /**
   * Implements hook_preprocess_webform_html_editor_markup().
   */
  #[Hook('preprocess_webform_html_editor_markup')]
  public function preprocessWebformHtmlEditorMarkup(array &$variables): void {
    $markup = (string) $variables['markup'];
    $processed = HtmlContentProcessor::processHtmlContent($markup);
    $variables['content'] = [
      '#type' => 'component',
      '#component' => 'civictheme_starter:basic-content',
      '#props' => [
        // Rendered fragments (element markup, descriptions, more content,
        // confirmation messages) inherit their width and rhythm from the
        // surrounding layout: a nested grid container would misalign them
        // and vertical spacing is the consumer's concern, not the
        // fragment's.
        'is_contained' => FALSE,
      ],
      '#slots' => [
        'content' => [
          '#markup' => $processed,
        ],
      ],
    ];
  }

  /**
   * Implements template_preprocess_webform_element_more().
   */
  #[Hook('preprocess_webform_element_more')]
  public function preprocessWebformElementMore(array &$variables): void {
    // The theme propagation keys arrive inside the element attributes, which
    // webform has already converted to an Attribute object: extract the
    // theme and keep the keys from printing as attributes.
    $attributes = $variables['attributes'] ?? NULL;
    if (!$attributes instanceof Attribute) {
      return;
    }
    if (isset($attributes['#civictheme_theme'])) {
      $civictheme_theme = (string) $attributes['#civictheme_theme']->value();
      if (in_array($civictheme_theme, CivicthemeOptions::themeOptions(TRUE))) {
        $variables['theme'] = $civictheme_theme;
      }
    }
    $attributes->removeAttribute('#civictheme_theme', '#component_theme');
  }

  /**
   * Implements template_preprocess_webform_section().
   */
  #[Hook('preprocess_webform_section')]
  public function preprocessWebformSection(array &$variables): void {
    $variables['theme'] = FormElementHelper::getComponentTheme($variables['element'] ?? []);
    unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
  }

  /**
   * Implements template_preprocess_webform_horizontal_rule().
   */
  #[Hook('preprocess_webform_horizontal_rule')]
  public function preprocessWebformHorizontalRule(array &$variables): void {
    $variables['theme'] = FormElementHelper::getComponentTheme($variables['element'] ?? []);
    unset($variables['attributes']['#civictheme_theme'], $variables['attributes']['#component_theme']);
  }

  /**
   * Implements hook_preprocess_container__webform_container().
   */
  #[Hook('preprocess_container__webform_container')]
  public function preprocessContainerWebformContainer(array &$variables): void {
    if (!FormElementHelper::isWebformElement($variables['element'])) {
      return;
    }
    $this->applyWebformOverrides($variables);
    $variables['content'] = $variables['children'];
    unset($variables['children']);
  }

  /**
   * Allows override of CivicTheme component settings from webform settings.
   *
   * These are set via customer settings (yaml) in webform elements.
   *
   * @see https://www.drupal.org/project/civictheme/issues/3581086
   */
  protected function applyWebformOverrides(array &$variables): void {
    $element = $variables['element'];
    if (!FormElementHelper::isWebformElement($element)) {
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

}
