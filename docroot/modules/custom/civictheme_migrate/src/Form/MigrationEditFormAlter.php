<?php

namespace Drupal\civictheme_migrate\Form;

use Drupal\civictheme_migrate\MigrationSchema;
use Drupal\civictheme_migrate\Utility;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Alter migration UI form provided by migrate_plus.
 */
class MigrationEditFormAlter implements ContainerInjectionInterface {

  use StringTranslationTrait;
  use DependencySerializationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Form alter implementation.
   *
   * Provide 'Update sources' fieldset to allow updating of sources using
   * URLs and files within the migration edit UI.
   */
  public function formAlter(array &$form, FormStateInterface &$form_state, string $form_id): void {
    if (!$this->shouldAlter($form, $form_state)) {
      return;
    }

    $migration_entity = $form_state->getFormObject()->getEntity();

    $form['source_update'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Update sources'),
      '#description' => $this->t('Update sources using files and/or URLs. Upon save, current migration sources will be replaced.'),
      '#description_display' => 'before',
      '#tree' => TRUE,
    ];

    $form['source_update']['urls'] = [
      '#title' => $this->t('Source as URLs'),
      '#description' => $this->t('Add sources as URLs, one per line. Only basic authentication is supported and should be added to the URLs.'),
      '#type' => 'textarea',
      '#rows' => 3,
      '#default_value' => Utility::arrayToMultiline($this->extractUrls($migration_entity->source['urls'] ?? [])),
      '#element_validate' => [
        // Convert string to array of values.
        [self::class, 'elementValidateValueToArray'],
        // Validate that every line is a valid URL.
        [self::class, 'elementValidateUrls'],
      ],
    ];

    $entity_type_id = str_replace('entity:', '', $migration_entity->destination['plugin'] ?? '');
    $bundle = $migration_entity->destination['default_bundle'] ?? $migration_entity->destination['validation_bundle'] ?? '';
    $migration_schema_id = !empty($entity_type_id) && !empty($bundle) ? MigrationSchema::idFromEntityTypeBundle($entity_type_id, $bundle) : NULL;
    $form['source_update']['files'] = [
      '#title' => $this->t('Source as files'),
      '#description' => $this->t('Upload one or multiple source files.'),
      '#type' => 'managed_file',
      '#multiple' => TRUE,
      '#progress_indicator' => 'bar',
      '#upload_location' => 'private://civictheme_migrate',
      '#default_value' => $this->extractFids($migration_entity->source['urls'] ?? []),
      '#upload_validators' => [
        // Validate allowed extension.
        'file_validate_extensions' => ['json'],
        // Validate that uploaded file follows expected schema.
        'civictheme_migrate_validate_migration_schema' => [$migration_schema_id],
      ],
    ];

    // Add custom entoty builder to mutate entity using the provided values.
    $form['#entity_builders'][] = [$this, 'entityBuilder'];
  }

  /**
   * Entity builder used to mutate migration entity to update source urls.
   */
  public function entityBuilder(string $entity_type_id, EntityInterface $entity, array $form, FormStateInterface $form_state): void {
    // Only process non-submitted form.
    if ($form_state->isSubmitted()) {
      return;
    }

    $source_update = $entity->source_update ?? NULL;
    unset($entity->source_update);

    if (!$source_update) {
      return;
    }

    $urls = [];

    if (!empty($source_update['files']['fids'])) {
      foreach ($source_update['files']['fids'] as $fid) {
        $file = $this->fileLoadByProperty('fid', $fid);
        if ($file) {
          // Store uploaded files as file URIs.
          $urls[] = $file->getFileUri();
        }
      }
    }

    if (!empty($source_update['urls'])) {
      // Store updated URLs as an array.
      $urls = array_merge($urls, Utility::multilineToArray($source_update['urls']));
    }

    if (!empty($urls)) {
      $source = $entity->get('source');
      $source['urls'] = $urls;
      $entity->set('source', $source);
    }
  }

  /**
   * Check if the form alter should apply.
   */
  protected function shouldAlter(array $form, FormStateInterface &$form_state): bool {
    $entity = $form_state->getFormObject()->getEntity();

    return $entity && $entity->source['plugin'] == 'url';
  }

  /**
   * Extract managed file ids from an array of entries.
   */
  protected function extractFids(array $entries): array {
    $urls = [];

    foreach ($entries as $entry) {
      if (!UrlHelper::isValid($entry, TRUE)) {
        $file = $this->fileLoadByProperty('uri', $entry);
        if ($file) {
          $urls[] = $file->id();
        }
      }
    }

    return $urls;
  }

  /**
   * Extract absolute URLs from an array of entries.
   */
  protected function extractUrls(array $entries): array {
    $urls = [];

    foreach ($entries as $entry) {
      if (UrlHelper::isValid($entry, TRUE)) {
        $urls[] = $entry;
      }
    }

    return $urls;
  }

  /**
   * Load a managed file by property.
   */
  protected function fileLoadByProperty(string $name, string $value): File|null {
    /** @var \Drupal\file\FileInterface[] $files */
    $files = $this->entityTypeManager->getStorage('file')->loadByProperties([
      $name => $value,
    ]);

    $file = reset($files) ?: NULL;

    return $file;
  }

  /**
   * Convert element value from multiline string to an array.
   */
  public static function elementValidateValueToArray(array $element, FormStateInterface $form_state): void {
    $form_state->setValueForElement($element, Utility::multilineToArray($element['#value']));
  }

  /**
   * Validate URLs for a form element.
   */
  public static function elementValidateUrls(array $element, FormStateInterface $form_state): void {
    $urls = $form_state->getValue(['source_update', 'urls']);
    if (!empty($urls)) {
      foreach ($urls as $url) {
        if (!UrlHelper::isValid($url, TRUE)) {
          $form_state->setError($element, t('Provided "@url" is not a valid absolute URL.', [
            '@url' => $url,
          ]));
        }
      }
    }
  }

}
