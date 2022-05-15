<?php

namespace Drupal\cs_demo;

use Drupal\Component\Utility\SortArray;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityInterface;

/**
 * Class CsDemoRepository.
 *
 * Repository class to manage demo items.
 *
 * @package Drupal\cs_demo
 * @SuppressWarnings(PHPMD)
 */
class CsDemoRepository {

  /**
   * The repository singleton.
   *
   * @var \Drupal\cs_demo\CsDemoRepository
   */
  protected static $instance = NULL;

  /**
   * Array of discovered information about entities.
   *
   * @var array
   */
  protected $info = [];

  /**
   * The entities.
   *
   * @var array
   */
  protected $entities = [];

  /**
   * Path to content directory.
   *
   * @var string
   */
  protected $contentBasePath;

  /**
   * CsDemoRepository constructor.
   *
   * @param string $contentBasePath
   *   Optional path to content directory.
   */
  public function __construct($contentBasePath = NULL) {
    if ($contentBasePath) {
      $this->setContentBasePath($contentBasePath);
    }
    else {
      $this->setContentBasePath(drupal_get_path('module', 'cs_demo') . '/content');
    }
    $this->entities = $this->loadEntities();
  }

  /**
   * Get path to content directory.
   *
   * @return string
   *   Path to content directory.
   */
  public function getContentBasePath() {
    return $this->contentBasePath;
  }

  /**
   * Set path to content directory.
   *
   * @param string $contentBasePath
   *   Path to content directory.
   */
  public function setContentBasePath($contentBasePath) {
    $this->contentBasePath = $contentBasePath;
  }

  /**
   * Get the repository instance.
   *
   * @param string $contentBasePath
   *   Optional path to content directory.
   *
   * @return \Drupal\cs_demo\CsDemoRepository
   *   The repository.
   */
  public static function getInstance($contentBasePath = NULL) {
    if (!static::$instance) {
      static::$instance = \Drupal::service('class_resolver')
        ->getInstanceFromDefinition(static::class);
      if ($contentBasePath) {
        static::$instance->setContentBasePath($contentBasePath);
      }
    }

    return static::$instance;
  }

  /**
   * Get information about entities.
   *
   * @param bool $reset
   *   Flag to reset previously collected information.
   *
   * @return array
   *   Array of information about entities.
   */
  public function getInfo($reset = FALSE) {
    if (empty($this->info) || $reset) {
      $this->info = $this->collectInfo();
    }

    return $this->info;
  }

  /**
   * Find info for provided entity type and bundle.
   *
   * @param string $entity_type
   *   Entity type.
   * @param null|string $bundle
   *   Bundle.
   *
   * @return bool|mixed
   *   Array of info about an entity or FALSE if no such entity was found.
   */
  public function findInfo($entity_type, $bundle = NULL) {
    $bundle = $bundle ?: $entity_type;

    foreach ($this->getInfo() as $item) {
      if ($item['entity_type'] == $entity_type && $item['bundle'] == $bundle) {
        return $item;
      }
    }

    return FALSE;
  }

  /**
   * Process creation of specified entities.
   *
   * @param array $filter
   *   (optional) Multi-dimensional array of filtered items to process.
   *   The First level key is an entity type and the second is a bundle. Value
   *   is a boolean TRUE.
   * @param bool $clear_caches
   *   Flag to clear caches after all items were created.
   *
   * @return int
   *   Number of created items.
   */
  public function create(array $filter = [], $clear_caches = TRUE) {
    $info = $this->getInfo();

    $total = 0;
    foreach ($info as $item) {
      // Filter-out any items that have not been provided in the filter.
      if (!empty($filter) && empty($filter[$item['entity_type']][$item['bundle']])) {
        continue;
      }
      $total += $this->createSingle($item);
    }

    if ($clear_caches) {
      $this->clearCaches();
    }

    \Drupal::messenger()->addMessage('Created all demo content.');

    return $total;
  }

  /**
   * Process creation of specified entities in a batch.
   */
  public function createBatch($info = FALSE) {
    $info = $info ? $info : $this->getInfo();
    // Every info item needs to be set only once.
    CsDemoBatch::set('create', $info, 1);
  }

  /**
   * Process single entity definition.
   *
   * @param array $info
   *   Entity definition information.
   */
  public function createSingle(array $info) {
    if (!empty($info['#file']) && file_exists($info['#file'])) {
      require_once $info['#file'];
    }
    $demo_entities = $info['#callback']();
    \Drupal::messenger()->addMessage(sprintf('Created demo entities "%s" with bundle "%s"', $info['entity_type'], $info['bundle']));
    $this->addEntities($demo_entities, $info['#tracking']);
    $total = count($demo_entities);
    unset($demo_entities);
    return $total;
  }

  /**
   * Process removal of specified entities.
   */
  public function remove($info = FALSE) {
    $info = $info ? $info : $this->getInfo();

    foreach ($info as $item) {
      $this->removeSingle($item);
    }

    // Reload entities.
    $this->entities = $this->loadEntities();

    $this->clearCaches();
    \Drupal::messenger()->addMessage('Removed all demo content.');
  }

  /**
   * Remove specified demo entities.
   */
  public function removeBatch($info = FALSE) {
    $info = $info ? $info : $this->getInfo();
    CsDemoBatch::set('remove', $info, count($info));
  }

  /**
   * Cleanup content.
   */
  public function removeSingle($info) {
    $this->removeTrackedEntities($info['entity_type'], $info['bundle']);
    \Drupal::messenger()->addMessage(sprintf('Removed all demo entities "%s" in bundle "%s"', $info['entity_type'], $info['bundle']));
  }

  /**
   * Check if the repository is empty.
   *
   * @return bool
   *   TRUE if there are no entities in the repository.
   */
  public function isEmpty() {
    return Database::getConnection()->select('cs_demo')->countQuery()->execute()->fetchField() == 0;
  }

  /**
   * Clear all required caches.
   */
  public function clearCaches() {
    $caches = [
      'data',
      'dynamic_page_cache',
      'entity',
      'page',
      'render',
    ];

    foreach ($caches as $cache) {
      if (\Drupal::hasService('cache.' . $cache)) {
        \Drupal::cache($cache)->deleteAll();
      }
    }
  }

  /**
   * Return an array of default weights.
   */
  protected function getDefaultWeights() {
    return [
      'user' => -100,
      'menu' => -90,
      'taxonomy_term' => -80,
      'media' => -50,
      'node' => 0,
      'block_content' => -10,
    ];
  }

  /**
   * Collect information about entities to process.
   *
   * @return array
   *   Array of information records about each entity type and bundle.
   */
  protected function collectInfo() {
    $base_path = $this->contentBasePath;

    $default_weights = $this->getDefaultWeights();

    $info = \Drupal::service('entity_type.bundle.info')->getAllBundleInfo();
    $available_demo = [];
    foreach ($info as $entity_type => $bundles) {
      foreach (array_keys($bundles) as $bundle) {
        $demo_inc = $base_path . '/' . $entity_type . '/' . $bundle . '.inc';

        if (!file_exists($demo_inc)) {
          continue;
        }
        require_once $demo_inc;

        $demo_function = 'cs_demo_create_' . $entity_type . '_' . $bundle;

        if (function_exists($demo_function)) {
          $key = $entity_type . '__' . $bundle;
          $available_demo[$key] = [
            'entity_type' => $entity_type,
            'bundle' => $bundle,
            '#callback' => $demo_function,
            '#tracking' => TRUE,
            '#weight' => $default_weights[$entity_type] ?? 0,
            '#file' => $demo_inc,
          ];

          $demo_weight_function = 'cs_demo_create_' . $entity_type . '_' . $bundle . '_weight';
          if (function_exists($demo_weight_function)) {
            $available_demo[$key]['#weight'] = $demo_weight_function();
          }

          $demo_tracking_function = 'cs_demo_create_' . $entity_type . '_' . $bundle . '_tracking';
          if (function_exists($demo_tracking_function)) {
            $available_demo[$key]['#tracking'] = $demo_tracking_function();
          }
        }
      }
    }

    uasort($available_demo, [SortArray::class, 'sortByWeightProperty']);

    return $available_demo;
  }

  /**
   * Load all entities from the database.
   */
  protected function loadEntities($load_entities = TRUE) {
    $result = [];
    $data = Database::getConnection()
      ->select('cs_demo', 'demo')
      ->fields('demo')
      ->execute()
      ->fetchAll(\PDO::FETCH_ASSOC);

    // Collect all entity ids.
    foreach ($data as $item) {
      $result[$item['entity_type']][$item['bundle']][$item['entity_id']] = $item['entity_id'];
    }

    if (!$load_entities) {
      return $result;
    }

    // Traverse trough results and load entities.
    $entity_type_manager = \Drupal::entityTypeManager();
    foreach ($result as $entity_type_id => $bundles) {
      foreach ($bundles as $bundle_id => $entity_ids) {
        $loaded_entities = $entity_type_manager
          ->getStorage($entity_type_id)
          ->loadMultiple($entity_ids);
        if (!empty($loaded_entities)) {
          $result[$entity_type_id][$bundle_id] = $loaded_entities;
        }
      }
    }

    return $result;
  }

  /**
   * Add a demo entity to the repository.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   * @param string $entity_type
   *   Override entity type with a custom value.
   * @param string $bundle
   *   Override bundle with a custom value.
   * @param bool $tracking
   *   Whether to track the entities.
   */
  protected function addEntity(EntityInterface $entity, $entity_type = NULL, $bundle = NULL, $tracking = TRUE) {
    $entity_type = $entity_type ?: $entity->getEntityTypeId();
    $bundle = $bundle ?: $entity->bundle();

    $this->entities[$entity_type][$bundle][$entity->id()] = $entity;
    if ($tracking) {
      $this->trackEntity($entity);
    }
  }

  /**
   * Add multiple demo entities to the repository.
   *
   * @param array $entities
   *   The array of entities.
   * @param bool $tracking
   *   Whether to track the entities.
   */
  protected function addEntities(array $entities, $tracking = TRUE) {
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    foreach ($entities as $entity) {
      $this->addEntity($entity, NULL, NULL, $tracking);
    }
  }

  /**
   * Add multiple demo entities to the repository without tracking.
   *
   * Used to update in-memory entities without writing them to DB.
   *
   * @param array $entities
   *   The array of entities.
   */
  public function addEntitiesNoTracking(array $entities) {
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    foreach ($entities as $entity) {
      $this->addEntity($entity, NULL, NULL, FALSE);
    }
  }

  /**
   * Ger demo entities.
   *
   * @param string $entity_type
   *   Entity type ID, eg. node or taxonomy_term.
   * @param string $bundle
   *   Bundle, eg. Page, lading_page.
   *
   * @return array
   *   The list of entities.
   */
  public function getEntities($entity_type = NULL, $bundle = NULL) {
    if (empty($this->entities)) {
      $this->entities = $this->loadEntities();
    }

    if ($entity_type) {
      if (isset($this->entities[$entity_type])) {
        if ($bundle) {
          if (isset($this->entities[$entity_type][$bundle])) {
            return $this->entities[$entity_type][$bundle];
          }

          return [];
        }

        return $this->entities[$entity_type];
      }

      return [];
    }

    return $this->entities;
  }

  /**
   * Track the entity permanently in the demo table.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   */
  protected function trackEntity(EntityInterface $entity) {
    try {
      $data = [
        'entity_type' => $entity->getEntityTypeId(),
        'bundle' => $entity->bundle(),
        'entity_id' => $entity->id(),
      ];
      Database::getConnection()->merge('cs_demo')
        ->keys($data)
        ->updateFields($data)
        ->execute();
    }
    catch (\Exception $exception) {
      watchdog_exception('cs_demo', $exception);
    }
  }

  /**
   * Remove all tracked entities.
   */
  protected function removeTrackedEntities($entity_type = NULL, $bundle = NULL, $entity_id = NULL) {
    $bundle = $bundle ?: $entity_type;

    try {
      if (!Database::getConnection()->schema()->tableExists('cs_demo')) {
        return;
      }

      $query = Database::getConnection()->select('cs_demo', 'demo')
        ->fields('demo');

      if ($entity_type) {
        $query->condition('entity_type', $entity_type);
      }
      if ($bundle) {
        $query->condition('bundle', $bundle);
      }
      if ($entity_id) {
        $query->condition('entity_id', $entity_id);
      }

      $query = $query->execute();

      $results = $query->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($results as $result) {
        try {
          $entity = \Drupal::entityTypeManager()->getStorage($result['entity_type'])
            ->load($result['entity_id']);
          if ($entity) {
            $entity->delete();
            unset($this->entities[$entity_type][$bundle][$entity_id]);
          }
        }
        catch (\Exception $exception) {
          watchdog_exception('cs_demo', $exception);
        }
      }
    }
    catch (\Exception $exception) {
      watchdog_exception('cs_demo', $exception);
    }
  }

}
