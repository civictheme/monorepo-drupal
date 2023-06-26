<?php

namespace Drupal\civictheme_migrate\Form;

use Drupal\civictheme_migrate\Utility;
use Drupal\civictheme_migrate\Validator\MigrationSchemaManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The migration schema manager.
   *
   * @var \Drupal\civictheme_migrate\Validator\MigrationSchemaManagerInterface
   */
  protected $migrationSchemaManager;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\civictheme_migrate\Validator\MigrationSchemaManagerInterface $migration_schema_manager
   *   The migration schema manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, MigrationSchemaManagerInterface $migration_schema_manager) {
    parent::__construct($config_factory);
    $this->migrationSchemaManager = $migration_schema_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('civictheme_migrate.migration_schema_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'civictheme_migrate_settings';
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
    $config = $this->config('civictheme_migrate.settings');

    $form['#tree'] = TRUE;

    $form['remote_authentication'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Remote authentication'),
    ];

    $form['remote_authentication']['type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Authentication type'),
      '#description' => $this->t('Sourcing migration assets from remote endpoints may require authentication. Select the type below and provide authentication details.'),
      '#options' => [
        'none' => $this->t('None'),
        'basic' => $this->t('Basic authentication'),
      ],
      '#default_value' => $config->get('remote_authentication')['type'] ?? 'none',
    ];

    $form['remote_authentication']['basic'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic authentication'),
      '#states' => [
        'visible' => [
          ':input[name="remote_authentication[type]"]' => ['value' => 'basic'],
        ],
      ],
    ];

    $form['remote_authentication']['basic']['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#states' => [
        'required' => [
          ':input[name="remote_authentication[type]"]' => ['value' => 'basic'],
        ],
      ],
      '#default_value' => $config->get('remote_authentication')['basic']['username'] ?? NULL,
    ];

    $form['remote_authentication']['basic']['password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Password'),
      '#states' => [
        'required' => [
          ':input[name="remote_authentication[type]"]' => ['value' => 'basic'],
        ],
      ],
      '#default_value' => $config->get('remote_authentication')['basic']['password'] ?? NULL,
    ];

    $form['local_domains'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Local domains'),
      '#description' => $this->t('Local domains to use when resolving absolute URLs. One domain per line.'),
      '#default_value' => Utility::arrayToMultiline($config->get('local_domains') ?? []),
    ];

    $form['migration_schema'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Migration schemas'),
    ];

    $form['migration_schema']['description'] = [
      '#markup' => $this->t('The migration schema outlines the source data structure for migrations. It is updated when the CivicTheme content model changes.<br/><br/>Migration schema files can be found in %dir directory.', [
        '%dir' => $this->migrationSchemaManager->getDirectory(),
      ]),
    ];

    $schemas = $this->migrationSchemaManager->getSchemas();

    foreach ($schemas as $schema) {
      $form['migration_schema'][$schema->getId()] = [
        '#type' => 'details',
        '#title' => $schema->getId(),
      ];

      $form['migration_schema'][$schema->getId()]['content'] = [
        '#type' => 'markup',
        '#markup' => '<pre><code>' . $schema->formatData() . '</code></pre>',
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('civictheme_migrate.settings')
      ->set('remote_authentication', $form_state->getValue('remote_authentication'))
      ->save();

    $this->config('civictheme_migrate.settings')
      ->set('local_domains', Utility::multilineToArray($form_state->getValue('local_domains')))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
