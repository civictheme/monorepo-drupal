<?php

declare(strict_types=1);

namespace Drupal\civictheme;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\paragraphs\ParagraphInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CivicTheme utilities.
 */
final class CivicthemeUpdateHelper implements ContainerInjectionInterface {

  /**
   * ConfigEntityUpdater constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger.
   */
  public function __construct(protected EntityTypeManagerInterface $entityTypeManager, protected LoggerInterface $logger) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')->get('action')
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
   * @param callable $finish_callback
   *   Finish callback called when batch finished.
   * @param int $batch_size
   *   Batch size. Defaults to 10.
   * @param int|null $limit
   *   Limit. Defaults to no limit.
   *
   * @return string|null
   *   Return log message on the last pass of the update.
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   */
  public function update(array &$sandbox, $entity_type, array $entity_bundles, callable $start_callback, callable $process_callback, callable $finish_callback, int $batch_size = 10, ?int $limit = NULL): ?string {
    $storage = $this->entityTypeManager->getStorage($entity_type);

    if (!isset($sandbox['entities'])) {
      $query = $storage->getQuery()->accessCheck(FALSE)->condition('type', $entity_bundles, 'IN');
      if ($limit) {
        $query->range(0, $limit);
      }

      $sandbox['entities'] = $query->execute();

      $sandbox['max'] = count($sandbox['entities']);
      $sandbox['batch'] = 0;

      $sandbox['results']['processed'] = [];
      $sandbox['results']['updated'] = [];
      $sandbox['results']['skipped'] = [];

      call_user_func($start_callback, $this);
    }

    ++$sandbox['batch'];

    /** @var \Drupal\Core\Entity\EntityInterface[] $entities */
    $entities = $storage->loadMultiple(array_splice($sandbox['entities'], 0, $batch_size));
    foreach ($entities as $entity) {
      $sandbox['results']['processed'][] = $entity->id();

      // Process callback may return boolean FALSE to consider this entity as
      // being skipped during processing.
      $process_return = call_user_func($process_callback, $this, $entity);
      $sandbox['results'][$process_return === TRUE ? 'updated' : 'skipped'][] = $entity->id();
    }

    $sandbox['#finished'] = empty($sandbox['entities']) ? 1 : ($sandbox['max'] - count($sandbox['entities'])) / $sandbox['max'];

    if ($sandbox['#finished'] >= 1) {
      $log = call_user_func($finish_callback, $this);

      $log = (string) new FormattableMarkup("%finished\n<br> Update ran in %batches batch(es):\n<br>   Processed: %processed %processed_ids\n<br>   Updated: %updated %updated_ids\n<br>   Skipped: %skipped %skipped_ids\n<br>", [
        '%finished' => $log ?? '',
        '%batches' => $sandbox['batch'],
        '%processed' => count($sandbox['results']['processed']),
        '%processed_ids' => count($sandbox['results']['processed']) > 0 ? '(' . implode(', ', $sandbox['results']['processed']) . ')' : '',
        '%updated' => count($sandbox['results']['updated']),
        '%updated_ids' => count($sandbox['results']['updated']) > 0 ? '(' . implode(', ', $sandbox['results']['updated']) . ')' : '',
        '%skipped' => count($sandbox['results']['skipped']),
        '%skipped_ids' => count($sandbox['results']['skipped']) > 0 ? '(' . implode(', ', $sandbox['results']['skipped']) . ')' : '',
      ]);
      $this->logger->info($log);

      return $log;
    }

    return NULL;
  }

  /**
   * Update field configs from path.
   *
   * @param array $configs
   *   Array of configs.
   * @param string $config_path
   *   Path to config file.
   */
  public function createConfigs(array $configs, string $config_path): void {
    $source = new FileStorage($config_path);

    // Check if field already exported in config/sync.
    foreach ($configs as $config => $type) {
      /** @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface */
      $storage = $this->entityTypeManager->getStorage($type);
      $config_read = $source->read($config);
      $id = substr($config, strpos($config, '.', 6) + 1);
      if (is_array($config_read) && $storage->load($id) == NULL) {
        $config_entity = $storage->createFromStorageRecord($config_read);
        $config_entity->save();
      }
    }
  }

  /**
   * Delete field configs after content update.
   *
   * @param array $configs
   *   Array of configs.
   */
  public function deleteConfig(array $configs): void {
    foreach ($configs as $config => $type) {
      // Check if field already exported to config.
      $storage = $this->entityTypeManager->getStorage($type);
      $id = substr($config, strpos($config, '.', 6) + 1);
      $config_read = $storage->load($id);
      if ($config_read != NULL) {
        $config_read->delete();
      }
    }
  }

  /**
   * Update form and group display.
   *
   * @param string $entity_type
   *   Entity type to update.
   * @param string $bundle
   *   Bundle to update.
   * @param array $field_config
   *   Array of field configs.
   * @param array|null $group_config
   *   Optional array of group configs.
   */
  public function updateFormDisplayConfig(string $entity_type, string $bundle, array $field_config, array $group_config = NULL): void {
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $form_display = $this->entityTypeManager
      ->getStorage('entity_form_display')
      ->load($entity_type . '.' . $bundle . '.default');

    // @phpstan-ignore-next-line
    if (!$form_display) {
      return;
    }

    foreach ($field_config as $field => $replacements) {
      $component = $form_display->getComponent($field);
      $component = $component ? array_replace_recursive($component, $replacements) : $replacements;
      $form_display->setComponent($field, $component);

      if ($group_config) {
        $field_group = $form_display->getThirdPartySettings('field_group');
        foreach ($group_config as $group_name => $group_config_item) {
          if (!empty($field_group[$group_name]['children'])) {
            $field_group[$group_name]['children'] = array_merge($field_group[$group_name]['children'], $group_config_item);
            $form_display->setThirdPartySetting('field_group', $group_name, $field_group[$group_name]);
          }
        }
      }
    }

    $form_display->save();
  }

  /**
   * Copy field content from one field to another.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *   Entity to update.
   * @param array $mappings
   *   Array of field names with source field names as keys and destination
   *   field names as values.
   *
   * @return bool
   *   TRUE if the entity was updated, FALSE otherwise.
   */
  public function copyFieldContent(FieldableEntityInterface $entity, array $mappings): bool {
    $updated = FALSE;

    foreach ($mappings as $src_field_name => $dst_field_name) {
      $src_field_value = civictheme_get_field_value($entity, $src_field_name, TRUE);
      if ($entity->hasField($dst_field_name) && !is_null($src_field_value)) {
        $entity->{$dst_field_name} = $src_field_value;
        $updated = TRUE;
      }
    }

    if ($updated) {
      $entity->save();
    }

    return $updated;
  }

  /**
   * Create a paragraph.
   *
   * @param array $paragraph_values
   *   The content string.
   *
   * @return \Drupal\paragraphs\ParagraphInterface|null
   *   Created paragraph.
   */
  public function createContentParagraph(array $paragraph_values): ?ParagraphInterface {
    $paragraph = $this->entityTypeManager->getStorage('paragraph')->create($paragraph_values);
    $paragraph->save();
    if ($paragraph instanceof ParagraphInterface) {
      return $paragraph;
    }
    return NULL;
  }

}
