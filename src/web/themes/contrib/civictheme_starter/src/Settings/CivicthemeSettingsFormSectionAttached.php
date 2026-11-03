<?php

declare(strict_types=1);

namespace Drupal\civictheme_starter\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to attach global elements.
 */
class CivicthemeSettingsFormSectionAttached extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function form(array &$form, FormStateInterface $form_state): void {
    $form['#attached']['library'][] = 'civictheme_starter/theme-settings';
  }

  /**
   * {@inheritdoc}
   */
  public function weight(): int {
    return 0;
  }

}
