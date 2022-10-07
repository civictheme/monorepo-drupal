<?php

namespace Drupal\civictheme_migration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure CivicTheme Migration from Merlin Migration JSON files.
 */
class CivicThemeMigrationConfigurationForm extends ConfigFormBase {

  /**
   * Gets label for retrieve files label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Retrieve files button label.
   */
  protected function getRetrieveFilesLabel() {
    return $this->t('Retrieve files');
  }

  /**
   * Gets label for generate configuration label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Generation configuration button label.
   */
  protected function getGenerateConfigurationLabel() {
    return $this->t('Generate configuration');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'civictheme_migration_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['civictheme_migration'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'system_config_form';

    $form['type'] = [
      '#title' => $this->t('Merlin generated content files'),
      '#description' => $this->t('Upload JSON generated files or connect to a remote URLS to retrieve Merlin JSON files'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'remote' => $this->t('Remote'),
        'local' => $this->t('Local'),
      ],
    ];

    $form['local'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Upload Merlin UI migration files'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'local'],
        ],
      ],
    ];

    $form['remote'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Connect to remote Merlin UI API to retrieve files'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'remote'],
        ],
      ],
    ];

    $form['remote']['auth_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Remote files authentication type'),
      '#options' => [
        '' => $this->t('None'),
        'basic' => $this->t('Basic authentication'),
        'password' => $this->t('Username and password'),
        'token' => $this->t('API token'),
      ],
    ];

    $form['remote']['auth_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#states' => [
        'visible' => [
          ':input[name="auth_type"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
      ],
    ];

    $form['remote']['auth_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#states' => [
        'visible' => [
          ':input[name="auth_type"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
      ],
    ];

    $form['remote']['auth_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Token'),
      '#states' => [
        'visible' => [
          ':input[name="auth_type"]' => ['value' => 'token'],
        ],
      ],
    ];

    $form['remote']['endpoint'][] = [
      '#type' => 'textarea',
      '#title' => $this->t('Merlin Generated JSON Endpoints'),
      '#description' => $this->t('Comma separated list of endpoints'),
    ];

    $form['remote']['actions']['#type'] = 'actions';
    $form['remote']['actions']['retrieve_files'] = [
      '#type' => 'submit',
      '#value' => $this->getRetrieveFilesLabel(),
      '#button_type' => 'primary',
    ];

    $form['local']['merlin_configuration_files'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload Merlin JSON Files'),
      '#multiple' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->getGenerateConfigurationLabel(),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->isRetrieveFilesSubmit($form_state)) {
      $this->messenger()->addStatus($this->t('Merlin configuration files have been retrieved', [':endpoint' => $form_state->getValue('endpoint')]));
    }
    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));

  }

  /**
   * Checks to see if the triggering element is the Retrieve files element.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return bool
   *   Whether the triggering button is the Retrieve files button.
   */
  protected function isRetrieveFilesSubmit(FormStateInterface $form_state): bool {
    $retrieve_files_label = $this->getRetrieveFilesLabel();
    $button_title = $form_state->getTriggeringElement()['#value'] ?? '';

    return (string) $button_title === (string) $retrieve_files_label;
  }

  /**
   * Checks to see if the triggering element is the Retrieve files element.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return bool
   *   Whether the triggering button is the Retrieve files button.
   */
  protected function isGenerateConfigurationSubmit(FormStateInterface $form_state): bool {
    $retrieve_files_label = $this->getGenerateConfigurationLabel();
    $button_title = $form_state->getTriggeringElement()['#value'] ?? '';

    return (string) $button_title === (string) $retrieve_files_label;
  }

}
