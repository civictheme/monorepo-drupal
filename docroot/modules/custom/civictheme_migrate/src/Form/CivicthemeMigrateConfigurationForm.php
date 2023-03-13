<?php

namespace Drupal\civictheme_migrate\Form;

use Drupal\civictheme_migrate\CivicthemeMigrateManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Serialization\Json;

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
        '_none' => $this->t('None'),
        'basic' => $this->t('Basic authentication'),
      ],
      '#default_value' => $config->get('remote')['auth_type'] ?? '_none',
    ];

    $form['remote']['auth_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#states' => [
        'visible' => [
          ':input[name="remote[auth_type]"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
        'required' => [
          ':input[name="remote[auth_type]"]' => [
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
          ':input[name="remote[auth_type]"]' => [
            ['value' => 'password'],
            'or',
            ['value' => 'basic'],
          ],
        ],
        'required' => [
          ':input[name="remote[auth_type]"]' => [
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
      '#title' => $this->t('Migration source Page content JSON URL endpoints'),
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
      '#title' => $this->t('Migration source Media Image JSON URL endpoints'),
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

    $configuration_files = $config->get('configuration_files') ? Json::decode($config->get('configuration_files')) : NULL;
    $configuration_files_count = $configuration_files ? count($configuration_files) : 1;
    $num_files = $form_state->get('num_files') ?? $configuration_files_count;
    if (!$form_state->get('num_files')) {
      $form_state->set('num_files', $num_files);
    }
    // We have to ensure that there is at least one fieldset.
    if ($num_files === NULL) {
      $form_state->set('num_files', 1);
      $num_files = 1;
    }

    // Get a list of fields that were removed.
    $removed_fields = $form_state->get('removed_fields');
    // If no fields have been removed yet we use an empty array.
    if ($removed_fields === NULL) {
      $form_state->set('removed_fields', []);
      $removed_fields = $form_state->get('removed_fields');
    }

    $form['#tree'] = TRUE;
    $form['configuration'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Upload extracted JSON Files'),
    ];

    $form['configuration']['configuration_files'] = [
      '#type' => 'fieldset',
      '#prefix' => '<div id="configurations-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $num_files; $i++) {
      if (in_array($i, $removed_fields)) {
        // Skip if field was removed and move to the next field.
        continue;
      }
      $form['configuration']['configuration_files'][$i] = [
        '#type' => 'fieldset',
        '#attributes' => [
          'class' => ['container-inline'],
        ],
      ];
      $form['configuration']['configuration_files'][$i]['json_configuration_type'] = [
        '#type' => 'select',
        '#options' => [
          'Node' => [
            'civictheme_page' => $this->t('Page'),
            // 'civictheme_event' => $this->t('Event'),
            // 'civictheme_alert' => $this->t('Alert'),
          ],
          'Media' => [
            'civictheme_media_image' => $this->t('Image'),
            // 'civictheme_media_document' => $this->t('Document'),
            // 'civictheme_media_audio' => $this->t('Audio'),
            // 'civictheme_media_video' => $this->t('Video'),
            // 'civictheme_media_remote_video' => $this->t('Remote video'),
            // 'civictheme_media_icon' => $this->t('Icon'),
          ],
          'Menu' => [
            // 'civictheme_menu_primary_navigation' => $this->t('Primary'),
            // 'civictheme_menu_secondary_navigation' => $this->t('Secondary'),
            // 'civictheme_menu_footer' => $this->t('Footer'),
          ],
        ],
      ];
      $form['configuration']['configuration_files'][$i]['json_configuration_files'] = [
        '#type' => 'managed_file',
        '#default_value' => $config->get('content_configuration_files'),
        '#multiple' => FALSE,
        '#progress_indicator' => 'bar',
        '#progress_message' => $this->t('Uploading files...'),
        '#upload_location' => 'private://civictheme_migrate',
        '#upload_validators' => [
          'file_validate_extensions' => ['json txt'],
        ],
      ];
      if (isset($configuration_files[$i])) {
        $form['configuration']['configuration_files'][$i]['json_configuration_type']['#default_value'] = $configuration_files[$i]['json_configuration_type'];
        $form['configuration']['configuration_files'][$i]['json_configuration_files']['#default_value'] = $configuration_files[$i]['json_configuration_files'];
      }
      if ($num_files > 1) {
        $form['configuration']['configuration_files'][$i]['actions'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove Row'),
          '#name' => $i,
          '#submit' => ['::removeCallback'],
          '#ajax' => [
            'callback' => '::addmoreCallback',
            'wrapper' => 'configurations-fieldset-wrapper',
          ],
          '#limit_validation_errors' => [],
        ];
      }
    }
    $form['configuration']['actions']['#type'] = 'actions';
    $form['configuration']['actions']['add_more'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'configurations-fieldset-wrapper',
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
   * Submit handler for the "remove" button.
   *
   * Removes the corresponding line.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = $trigger['#name'];

    unset($form['configuration']['configuration_files'][$indexToRemove]);
    $configurationFieldset = $form_state->getValue('configuration');
    unset($configurationFieldset['configuration_files'][$indexToRemove]);
    $form_state->setValue('configuration', $configurationFieldset);

    $removed_fields = $form_state->get('removed_fields');
    $removed_fields[] = $indexToRemove;
    $form_state->set('removed_fields', $removed_fields);

    $form_state->setRebuild();
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['configuration']['configuration_files'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $files = $form_state->get('num_files');
    $add_button = $files + 1;
    $form_state->set('num_files', $add_button);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($this->isGenerateMigrationSubmit($form_state)) {
      $content_files = $form_state->getValue('configuration')['configuration_files'];
      if (empty($content_files)) {
        $form_state->setError($form['configuration']['configuration_files'], $this->t('Migration files are required to generate a migration'));
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
      $migrations = $form_state->getValue('configuration')['configuration_files'] ?? [];
      $migration_types = [];
      if ($migrations) {
        foreach ($migrations as $migration) {
          $migration_types[$migration['json_configuration_type']][] = current($migration['json_configuration_files']);
        }
      }

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
      $config->set('configuration_files', Json::encode($form_state->getValue('configuration')['configuration_files']));
    }
    $config->set('migration_type', $form_state->getValue('migration_type'));
    $config->set('remote', [
      'auth_type' => $form_state->getValue('remote')['auth_type'] ?? '_none',
      'auth_username' => $form_state->getValue('remote')['auth_username'] ?? '',
      'auth_password' => $form_state->getValue('remote')['auth_password'] ?? '',
      'auth_token' => $form_state->getValue('remote')['auth_token'] ?? '' ?? '',
      'content_endpoint' => $form_state->getValue('remote')['content_endpoint'] ?? '',
      'media_endpoint' => $form_state->getValue('remote')['media_endpoint'] ?? '',
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
        'config_key' => 'civictheme_media_image',
      ],
      [
        'endpoint' => 'content_endpoint',
        'config_key' => 'civictheme_page',
      ],
    ];
    foreach ($file_types as $file_type) {
      $urls = explode("\n", str_replace("\r\n", "\n", $form_state->getValue($file_type['endpoint'])));
      $config = $this->config('civictheme_migrate.settings');
      $validators = [
        'file_validate_extensions' => ['json txt'],
        'civictheme_migrate_validate_json' => [$file_type['config_key']],
      ];
      try {
        $files = $this->migrationManager->retrieveRemoteFiles($urls, $validators);
        $existing_files = $config->get('configuration_files') ? Json::decode($config->get('configuration_files')) : [];
        $config->set('configuration_files', Json::encode(array_merge($existing_files[$file_type['config_key']], $files)));
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
