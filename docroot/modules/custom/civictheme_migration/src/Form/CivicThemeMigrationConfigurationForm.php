<?php

namespace Drupal\civictheme_migration\Form;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure CivicTheme Migration from Merlin Migration JSON files.
 */
class CivicThemeMigrationConfigurationForm extends ConfigFormBase {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The file storage service.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $fileStorage;

  /**
   * Yaml serializer.
   *
   * @var \Drupal\Component\Serialization\Yaml
   */
  protected Yaml $yaml;

  /**
   * Extemnsion Path resolver.
   *
   * @var \Drupal\civictheme_migration\Form\ExtensionPathResolver
   */
  protected ExtensionPathResolver $extensionPathResolver;

  /**
   * Config Importer.
   *
   * @var \Drupal\Core\Config\ConfigImporter
   */
  protected ConfigImporter $configImporter;

  /**
   * Migration Entity Storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $migrationStorage;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $client, FileSystemInterface $file_system, EntityStorageInterface $file_storage, Yaml $yaml_serializer, ExtensionPathResolver $extension_path_resolver, EntityStorageInterface $migration_storage) {
    parent::__construct($config_factory);
    $this->httpClient = $client;
    $this->fileSystem = $file_system;
    $this->fileStorage = $file_storage;
    $this->yaml = $yaml_serializer;
    $this->extensionPathResolver = $extension_path_resolver;
    $this->migrationStorage = $migration_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $container->get('config.factory'),
      $container->get('http_client'),
      $container->get('file_system'),
      $entity_type_manager->getStorage('file'),
      $container->get('serialization.yaml'),
      $container->get('extension.path.resolver'),
      $entity_type_manager->getStorage('migration'),
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
    return 'civictheme_migration_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['civictheme_migration.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'system_config_form';
    $config = $this->config("civictheme_migration.settings");

    $form['migration_type'] = [
      '#title' => $this->t('Where are your Merlin extracted content JSON files?'),
      '#description' => $this->t('Upload Merlin extracted content json files or connect to a remote URLS to retrieve'),
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
      '#title' => $this->t('Connect to remote Merlin UI API to retrieve extracted content JSON files'),
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

    $form['remote']['endpoint'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Merlin extracted content JSON URL endpoints'),
      '#description' => $this->t('One URL each line'),
      '#default_value' => $config->get('remote')['endpoint'] ?? NULL,
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

    $form['configuration_files'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload Merlin extracted content JSON Files'),
      '#default_value' => $config->get('configuration_files'),
      '#multiple' => TRUE,
      '#progress_indicator' => 'bar',
      '#progress_message'   => $this->t('Uploading files...'),
      '#upload_location' => 'private://civictheme_migration/',
      '#upload_validators'  => [
        'file_validate_extensions' => ['json txt'],
        'civictheme_migration_validate_json' => [],
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
      $files = $form_state->getValue('configuration_files');
      if (empty($files)) {
        $form_state->setError($form['configuration_files'], $this->t('Configuration files are required to generate a migration'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('civictheme_migration.settings');
    if ($this->isRetrieveFilesSubmit($form_state)) {
      $urls = explode("\r\n", $form_state->getValue('endpoint'));
      try {
        $files = $this->retrieveFiles($urls);
        $existing_files = $config->get('configuration_files') ?? [];
        $config->set('configuration_files', array_merge($existing_files, $files));
        $this->messenger()->addStatus($this->t('Merlin extracted content JSON files have been retrieved'));
      }
      catch (\Exception $exception) {
        $this->messenger()->addError($exception->getMessage());
      }
    }

    if ($this->isSaveConfigurationSubmit($form_state) || $this->isGenerateMigrationSubmit($form_state)) {
      $config->set('configuration_files', $form_state->getValue('configuration_files'));
    }
    $config->set('migration_type', $form_state->getValue('migration_type'));
    $config->set('remote', [
      'auth_type' => $form_state->getValue('auth_type'),
      'auth_username' => $form_state->getValue('auth_username'),
      'auth_password' => $form_state->getValue('auth_password'),
      'auth_token' => $form_state->getValue('auth_token'),
      'endpoint' => $form_state->getValue('endpoint'),
    ]);
    $config->save();
    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));

    if ($this->isGenerateMigrationSubmit($form_state)) {
      $this->generateMigrationConfigurationSubmit($form, $form_state);
    }

  }

  /**
   * Handles submission to generate and import migration configuration.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Formstate object.
   */
  protected function generateMigrationConfigurationSubmit(array &$form, FormStateInterface $form_state) {
    $file_ids = $form_state->getValue('configuration_files');
    $files = $this->fileStorage->loadMultiple($file_ids);
    $file_stream_wrappers = [];
    foreach ($files as $file) {
      $file_stream_wrappers[] = $file->getFileUri();
    }
    $migration_config_file_name = $this->extensionPathResolver->getPath('module', 'civictheme_migration') . '/assets/migrate_plus.migration.civictheme_page.yml';
    $migration_config = file_get_contents($migration_config_file_name);
    $migration_config = $this->yaml->decode($migration_config);
    $migration_config['source']['urls'] = $file_stream_wrappers;
    $existing_migration = $this->migrationStorage->load('civictheme_page_migration');
    if ($existing_migration === NULL) {
      $migration = $this->migrationStorage->create($migration_config);
      $migration->save();
      $this->messenger()->addStatus($this->t('Migration has been generated.'));
    }
    else {
      // Adding all keys in case we update the asset migration template.
      $existing_migration->set('process', $migration_config['process']);
      $existing_migration->set('source', $migration_config['source']);
      $existing_migration->set('destination', $migration_config['destination']);
      $existing_migration->save();
      $this->messenger()->addStatus($this->t('Migration has been updated.'));
    }
    $form_state->setRedirect('entity.migration.list', [
      'migration_group' => 'civictheme_migration',
    ]);
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

  /**
   * Retrieve a remote migration JSON configuration file and store locally.
   *
   * @param array $urls
   *   Array of remote migration files.
   *
   * @return array
   *   Array of file ids.
   *
   * @throws \Exception
   */
  protected function retrieveFiles(array $urls = []):array {
    $files = [];
    $auth_headers = $this->getAuthHeaders();
    foreach ($urls as $url) {
      $file = $this->httpClient->get($url, $auth_headers);
      $json = $file->getBody();
      $json_validate = json_decode($json);
      if ($json_validate === NULL) {
        throw new \Exception(sprintf('JSON file is malformed - %s', $url));
      }
      else {
        $filename = $this->generateFileName($url);
        $directory = 'private://civictheme_migration';
        if ($this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY)) {
          $uri = "$directory/$filename";
          $path = $this->fileSystem->saveData($json, $uri);
          $filename = basename($path);
          $uri = "$directory/$filename";
          /** @var \Drupal\file\FileInterface $file */
          $file = $this->fileStorage->create(['uri' => $uri]);
          $file->setPermanent();
          $file->save();
          $files[] = (int) $file->get('fid')->getString();
        }
      }
    }

    return $files;
  }

  /**
   * Gets the authentication headers and other options for retrieving files.
   *
   * @return array
   *   Authentication header options.
   */
  protected function getAuthHeaders():array {
    $config = $this->config('civictheme_migration.settings');
    $auth_type = $config->get('auth_type');
    if (empty($auth_type)) {
      return [];
    }
    switch ($auth_type) {
      case 'basic':
        return [
          'auth' => [
            $config->get('auth_username'),
            $config->get('auth_password'),
          ],
        ];

      // @todo implement other authentication methods as required.
      default:
        return [];
    }
  }

  /**
   * Generates a filename from a url JSON endpoint.
   *
   * @param string $url
   *   Endpoint url.
   *
   * @return string
   *   Generated file name.
   */
  protected function generateFileName($url):string {
    $url_parts = parse_url($url);
    $filename = !empty($url_parts['path']) ? preg_replace('/\//', '-', $url_parts['path']) : 'configuration-file' . time();
    if (strpos($filename, '-') === 0) {
      $filename = substr($filename, 1);
    }
    if (strpos($filename, '.json') === FALSE && strpos($filename, '.txt') === FALSE) {
      $filename .= '.json';
    }

    return $filename;
  }

}
