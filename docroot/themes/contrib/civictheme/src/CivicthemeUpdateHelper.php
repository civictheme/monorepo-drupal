<?php

namespace Drupal\civictheme;

use Drupal\Core\Config\FileStorage;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme utilities.
 */
class CivicthemeUpdateHelper implements ContainerInjectionInterface {

  /**
   * Defines a batch size 10.
   */
  const BATCH_SIZE = 10;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The number of entities to process in each batch.
   *
   * @var int
   */
  protected $batchSize;

  /**
   * ConfigEntityUpdater constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger.
   * @param int $batch_size
   *   The number of entities to process in each batch.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LoggerInterface $logger, $batch_size) {
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger;
    $this->batchSize = $batch_size;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')->get('action'),
      self::BATCH_SIZE
    );
  }

  /**
   * Updates configuration entities as part of a Drupal update.
   *
   * @param array $sandbox
   *   Stores information for batch updates.
   * @param string $entity_type
   *   Entity type to load.
   * @param array $entity_bundles
   *   Entity build to filter entities.
   * @param callable $start_callback
   *   Start callback function to call whne batch initialise.
   * @param callable $process_callback
   *   Process callback to process entity.
   * @param callable $finished_callback
   *   Finish callback called when batch finished.
   */
  public function update(array &$sandbox, $entity_type, array $entity_bundles, callable $start_callback, callable $process_callback, callable $finished_callback) {
    $storage = $this->entityTypeManager->getStorage($entity_type);

    // If the sandbox is empty, initialize it.
    if (!isset($sandbox['entities'])) {
      $sandbox['batch'] = 0;
      $sandbox['current_entity'] = 0;
      // Query to fetch all the manual_list paragraph ids.
      $query = $storage->getQuery()->accessCheck(FALSE)
        ->condition('type', $entity_bundles, 'in');
      $sandbox['entities'] = $query->execute();
      $sandbox['max'] = count($sandbox['entities']);

      $sandbox['results']['processed'] = [];
      $sandbox['results']['updated'] = [];
      $sandbox['results']['skipped'] = [];

      // Start callback.
      call_user_func($start_callback, $this);
    }

    $sandbox['batch']++;

    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entities = $storage->loadMultiple(array_splice($sandbox['entities'], 0, $this->batchSize));

    foreach ($entities as $entity) {
      $sandbox['results']['processed'][] = $entity->id();
      $sandbox['current_enity'] = $entity;
      // Process entity.
      call_user_func($process_callback, $this, $entity);
    }

    $sandbox['#finished'] = empty($sandbox['entities']) ? 1 : ($sandbox['max'] - count($sandbox['entities'])) / $sandbox['max'];

    if ($sandbox['#finished'] >= 1) {
      // Finiished callback.
      $log = call_user_func($finished_callback, $this);

      $log = new TranslatableMarkup("%finished\n<br> Update results ran in %batches batch(es):\n<br>   Processed: %processed %processed_ids\n<br>   Updated: %updated %updated_ids\n<br>   Skipped: %skipped %skipped_ids\n<br>", [
        '%finished' => $log,
        '%batches' => $sandbox['batch'],
        '%processed' => count($sandbox['results']['processed']),
        '%processed_ids' => count($sandbox['results']['processed']) ? '(' . implode(', ', $sandbox['results']['processed']) . ')' : '',
        '%updated' => count($sandbox['results']['updated']),
        '%updated_ids' => count($sandbox['results']['updated']) ? '(' . implode(', ', $sandbox['results']['updated']) . ')' : '',
        '%skipped' => count($sandbox['results']['skipped']),
        '%skipped_ids' => count($sandbox['results']['skipped']) ? '(' . implode(', ', $sandbox['results']['skipped']) . ')' : '',
      ]);
      $this->logger->info($log);

      return $log;
    }
  }

  /**
   * Updated required field configs.
   */
  public function createConfigs(array $configs, $config_path) {
    $source = new FileStorage($config_path);

    // Check if field already exported in config/sync.
    foreach ($configs as $config => $type) {
      /** @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface */
      $storage = $this->entityTypeManager->getStorage($type);
      $config_read = $source->read($config);
      $id = substr($config, strpos($config, '.', 6) + 1);
      if ($storage->load($id) == NULL) {
        $config_entity = $storage->createFromStorageRecord($config_read);
        $config_entity->save();
      }
    }
  }

  /**
   * Delete field configs after content update.
   */
  public function deleteConfig($sandbox, $configs) {
    if ($sandbox['#finished'] >= 1) {
      // Check if field already exported to config/sync.
      foreach ($configs as $config => $type) {
        $storage = $this->entityTypeManager->getStorage($type);
        $id = substr($config, strpos($config, '.', 6) + 1);
        $config_read = $storage->load($id);
        if ($config_read != NULL) {
          $config_read->delete();
        }
      }
    }
  }

  /**
   * Update field data for a given Paragraph.
   */
  public function updateFieldContent(&$sandbox, FieldableEntityInterface $entity, array $mappings) {
    $changed = FALSE;

    foreach ($mappings as $old_field => $new_field) {
      // Update fill width field value.
      if ($entity->hasField($new_field) && !is_null(civictheme_get_field_value($entity, $old_field, TRUE))) {
        $entity->{$new_field} = civictheme_get_field_value($entity, $old_field, TRUE);
        $changed = TRUE;
      }
    }

    if ($changed) {
      $entity->save();
      $sandbox['results']['updated'][] = $entity->id();
      return $changed;
    }

    $sandbox['results']['skipped'][] = $entity->id();

    return $changed;
  }

  /**
   * Update form and group display.
   */
  public function updateFormDisplay($entity_type, $bundle, array $field_config, array $group_config = NULL) {
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $form_display = $this->entityTypeManager
      ->getStorage('entity_form_display')
      ->load($entity_type . '.' . $bundle . '.default');

    if ($form_display) {
      foreach ($field_config as $field => $replacements) {
        $component = $form_display->getComponent($field);
        $component = $component ? array_replace_recursive($component, $replacements) : $replacements;
        $form_display->setComponent($field, $component);

        // Update the field groups.
        if ($group_config) {
          $field_group = $form_display->getThirdPartySettings('field_group');

          foreach ($group_config as $group_name => $group_config) {
            if (!empty($field_group[$group_name]['children'])) {
              $field_group[$group_name]['children'] = array_merge($field_group[$group_name]['children'], $group_config);
              $form_display->setThirdPartySetting('field_group', $group_name, $field_group[$group_name]);
            }
          }
        }
      }
      $form_display->save();
    }
  }

}
