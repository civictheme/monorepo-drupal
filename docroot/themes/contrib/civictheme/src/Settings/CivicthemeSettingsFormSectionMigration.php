<?php

namespace Drupal\civictheme\Settings;

use Drupal\Core\Form\FormStateInterface;

/**
 * CivicTheme settings section to provide Migration metadata.
 */
class CivicthemeSettingsFormSectionMigration extends CivicthemeSettingsFormSectionBase {

  /**
   * {@inheritdoc}
   */
  public function weight() {
    return 4;
  }

  /**
   * {@inheritdoc}
   */
  public function form(&$form, FormStateInterface &$form_state) {
    $form['migration'] = [
      '#type' => 'details',
      '#title' => $this->t('Migration'),
      '#open' => FALSE,
      '#tree' => TRUE,
      '#weight' => 40,
      '#description' => $this->t('Migrations may require access to internal field values not available in the HTML. Exposing such values adds <code>data</code> HTML attributes to HTML elements.'),
    ];

    $form['migration']['expose_metadata'] = [
      '#type' => 'checkbox',
      '#title' => 'Expose Migration metadata',
      '#default_value' => $this->themeConfigManager->load('migration.expose_metadata') ?? FALSE,
    ];
  }

}
