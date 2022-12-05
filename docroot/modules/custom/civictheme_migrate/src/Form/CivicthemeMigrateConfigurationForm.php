<?php

namespace Drupal\civictheme_migrate\Form;

use Drupal\civictheme_migrate\CivicthemeMigrateManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure CivicTheme Migrate.
 */
class CivicthemeMigrateConfigurationForm extends ConfigFormBase {

  /**
   * CivicTheme Migration Manager instance.
   *
   * @var \Drupal\civictheme_migrate\CivicthemeMigrateManager
   */
  protected $migrationManager;

  /**
   * Messenger interface.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CivicthemeMigrateManager $migration_manager, MessengerInterface $messenger) {
    parent::__construct($config_factory);
    $this->migrationManager = $migration_manager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('civictheme_migrate.migrate_manager'),
      $container->get('messenger')
    );
  }

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
  protected function getSaveConfigurationLabel() {
    return $this->t('Save configuration');
  }

  /**
   * Gets label for generate configuration label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Generation configuration button label.
   */
  protected function getGenerateMigrationLabel() {
    return $this->t('Generate migration');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'civictheme_migrate_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['civictheme_migrate.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'system_config_form';
    $config = $this->config("civictheme_migrate.settings");

    $form['migration_type'] = [
      '#title' => $this->t('Content files location'),
      '#description' => $this->t('Upload extracted content JSON files or connect to a remote URLS to retrieve'),
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => [
        'local' => $this->t('Local'),
        'remote' => $this->t('Remote'),
      ],
      '#default_value' => $config->get('migration_type'),
    ];

    $form['remote'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Connect to remote API to retrieve extracted content JSON files'),
      '#states' => [
        'visible' => [
          ':input[name="migration_type"]' => ['value' => 'remote'],
        ],
      ],
    ];

    $form['remote']['auth_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Remote files authentication type'),
      '#options' => [
        '' => $this->t('None'),
        'basic' => $this->t('Basic authentication'),
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
        'required' => [
          ':input[name="auth_type"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
      ],
      '#default_value' => $config->get('remote')['auth_username'] ?? NULL,
    ];

    $form['remote']['auth_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#description' => $this->t('Password is not shown after saving'),
      '#states' => [
        'visible' => [
          ':input[name="auth_type"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
        'required' => [
          ':input[name="auth_type"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
      ],
      '#default_value' => $config->get('remote')['auth_password'] ?? NULL,
    ];

    $form['remote']['content_endpoint'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Migration source page content JSON URL endpoints'),
      '#description' => $this->t('One URL each line'),
      '#default_value' => $config->get('remote')['content_endpoint'] ?? NULL,
      '#states' => [
        'required' => [
          ':input[name="migration_type"]' => ['value' => 'remote'],
        ],
      ],
    ];

    $form['remote']['media_endpoint'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Migration source media JSON URL endpoints'),
      '#description' => $this->t('One URL each line'),
      '#default_value' => $config->get('remote')['media_endpoint'] ?? NULL,
      '#states' => [
        'required' => [
          ':input[name="migration_type"]' => ['value' => 'remote'],
        ],
      ],
    ];

    $form['remote']['actions']['#type'] = 'actions';
    $form['remote']['actions']['retrieve_files'] = [
      '#type' => 'submit',
      '#value' => $this->getRetrieveFilesLabel(),
      '#button_type' => 'primary',
    ];

    $form['content_configuration_files'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload extracted content JSON Files'),
      '#default_value' => $config->get('content_configuration_files'),
      '#multiple' => TRUE,
      '#progress_indicator' => 'bar',
      '#progress_message'   => $this->t('Uploading files...'),
      '#upload_location' => 'private://civictheme_migrate/page',
      '#upload_validators'  => [
        'file_validate_extensions' => ['json txt'],
        'civictheme_migrate_validate_json' => ['civictheme_page'],
      ],
    ];

    $form['media_configuration_files'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload extracted media JSON Files'),
      '#default_value' => $config->get('media_configuration_files'),
      '#multiple' => TRUE,
      '#progress_indicator' => 'bar',
      '#progress_message'   => $this->t('Uploading files...'),
      '#upload_location' => 'private://civictheme_migrate/media_image',
      '#upload_validators'  => [
        'file_validate_extensions' => ['json txt'],
        'civictheme_migrate_validate_json' => ['civictheme_media'],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->getSaveConfigurationLabel(),
      '#button_type' => 'primary',
    ];
    $form['actions']['generate_migration'] = [
      '#type' => 'submit',
      '#value' => $this->getGenerateMigrationLabel(),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($this->isGenerateMigrationSubmit($form_state)) {
      $content_files = $form_state->getValue('content_configuration_files');
      if (empty($content_files)) {
        $form_state->setError($form['content_configuration_files'], $this->t('Page content migration files are required to generate a migration'));
      }

      $media_files = $form_state->getValue('content_configuration_files');
      if (empty($media_files)) {
        $form_state->setError($form['media_configuration_files'], $this->t('Media migration files are required to generate a migration'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->isRetrieveFilesSubmit($form_state)) {
      $this->retrieveRemoteFilesSubmit($form, $form_state);
    }

    $this->saveConfig($form, $form_state);

    if ($this->isGenerateMigrationSubmit($form_state)) {
      $migration_types = [
        'content' => $form_state->getValue('content_configuration_files'),
        'media' => $form_state->getValue('media_configuration_files'),
      ];

      $this->migrationManager->generateMigrations($migration_types);

      $form_state->setRedirect('entity.migration.list', [
        'migration_group' => 'civictheme_migrate',
      ]);
    }

  }

  /**
   * Helper to save migration file configuration.
   *
   * @param array $form
   *   Form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  protected function saveConfig(array &$form, FormStateInterface $form_state) {
    $config = $this->config('civictheme_migrate.settings');

    // Saving migration configuration files whether uploaded locally or
    // saved remotely.
    if ($this->isSaveConfigurationSubmit($form_state) || $this->isGenerateMigrationSubmit($form_state)) {
      $config->set('content_configuration_files', $form_state->getValue('content_configuration_files'));
      $config->set('media_configuration_files', $form_state->getValue('media_configuration_files'));
    }
    $config->set('migration_type', $form_state->getValue('migration_type'));
    $config->set('remote', [
      'auth_type' => $form_state->getValue('auth_type'),
      'auth_username' => $form_state->getValue('auth_username'),
      'auth_password' => $form_state->getValue('auth_password'),
      'auth_token' => $form_state->getValue('auth_token'),
      'content_endpoint' => $form_state->getValue('content_endpoint'),
      'media_endpoint' => $form_state->getValue('media_endpoint'),
    ]);
    $config->save();
    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
  }

  /**
   * Handles submission to retrieve remote migration files.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Formstate object.
   */
  protected function retrieveRemoteFilesSubmit(array &$form, FormStateInterface $form_state) {
    $file_types = [
      [
        'endpoint' => 'media_endpoint',
        'config_key' => 'media_configuration_files',
      ],
      [
        'endpoint' => 'content_endpoint',
        'config_key' => 'content_configuration_files',
      ],
    ];
    foreach ($file_types as $file_type) {
      $urls = explode("\n", str_replace("\r\n", "\n", $form_state->getValue($file_type['endpoint'])));
      $config = $this->config('civictheme_migrate.settings');
      try {
        $files = $this->migrationManager->retrieveRemoteFiles($urls, $form[$file_type['config_key']]['#upload_validators']);
        $existing_files = $config->get($file_type['config_key']) ?? [];
        $config->set($file_type['config_key'], array_merge($existing_files, $files));
        $this->messenger()->addStatus($this->t('Migration content files have been retrieved'));
        $config->save();
      }
      catch (\Exception $exception) {
        $this->messenger()->addError($exception->getMessage());
      }
    }

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
    $button_label = $this->getRetrieveFilesLabel();
    $button_title = $form_state->getTriggeringElement()['#value'] ?? '';

    return (string) $button_title === (string) $button_label;
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
  protected function isSaveConfigurationSubmit(FormStateInterface $form_state): bool {
    $button_label = $this->getSaveConfigurationLabel();
    $button_title = $form_state->getTriggeringElement()['#value'] ?? '';

    return (string) $button_title === (string) $button_label;
  }

  /**
   * Checks to see if the triggering element is the Generate Migration element.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return bool
   *   Whether the triggering button is the Retrieve files button.
   */
  protected function isGenerateMigrationSubmit(FormStateInterface $form_state): bool {
    $button_label = $this->getGenerateMigrationLabel();
    $button_title = $form_state->getTriggeringElement()['#value'] ?? '';

    return (string) $button_title === (string) $button_label;
  }

}
