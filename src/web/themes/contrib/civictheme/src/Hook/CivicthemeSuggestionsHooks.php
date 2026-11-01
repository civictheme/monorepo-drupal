<?php

declare(strict_types=1);

namespace Drupal\civictheme\Hook;

use Drupal\civictheme\Theme\Form\FormElementHelper;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Theme suggestions hook implementations for CivicTheme.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class CivicthemeSuggestionsHooks {

  /**
   * Implements hook_theme_suggestions_HOOK_alter() for block.
   */
  #[Hook('theme_suggestions_block_alter')]
  public function blockAlter(array &$suggestions, array $variables): void {
    // Add theme suggestions to all blocks whose bundle starts with
    // 'civictheme_'.
    if (!empty($variables['elements']['#base_plugin_id']) && $variables['elements']['#base_plugin_id'] == 'block_content') {
      $entity = $variables['elements']['content']['#block_content'] ?? NULL;
      if ($entity && str_starts_with((string) $entity->bundle(), 'civictheme_')) {
        $suggestions[] = 'block__' . $entity->bundle() . '__default';
      }
    }
  }

  /**
   * Implements hook_theme_suggestions_HOOK_alter() for container.
   */
  #[Hook('theme_suggestions_container_alter')]
  public function containerAlter(array &$suggestions, array $variables): void {
    if (!FormElementHelper::isWebformElement($variables['element'])) {
      return;
    }
    $suggestions[] = 'container__webform_container';
  }

  /**
   * Implements hook_theme_suggestions_form_element_alter().
   */
  #[Hook('theme_suggestions_form_element_alter')]
  public function formElementAlter(array &$suggestions, array $variables): void {
    // Provide a suggestion per element type so that element types can be
    // overridden with a single template (core itself provides none).
    if (!empty($variables['element']['#type'])) {
      $suggestions[] = 'form_element__' . $variables['element']['#type'];
    }
  }

  /**
   * Implements hook_theme_suggestions_fieldset_alter().
   */
  #[Hook('theme_suggestions_fieldset_alter')]
  public function fieldsetAlter(array &$suggestions, array $variables): void {
    // Checkboxes and radios groups render their composite fieldset wrapper
    // through the CivicTheme Field component in fieldset--checkboxes.html.twig
    // and fieldset--radios.html.twig.
    $group_types = ['checkboxes', 'radios'];
    if (isset($variables['element']['#type']) && in_array($variables['element']['#type'], $group_types)) {
      $suggestions[] = 'fieldset__' . $variables['element']['#type'];
    }
  }

}
