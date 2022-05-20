<?php

namespace Drupal\cs_demo;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BatchService.
 */
class CsDemoBatchService implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Drupal\Core\Messenger\MessengerInterface definition.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The core messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * Batch process callback.
   */
  public function processItem($batch_id, $entity_type, $bundle, $total, $current, &$context) {
    $repository = CsDemoRepository::getInstance();
    $repository->create([$entity_type => [$bundle => TRUE]]);

    $context['message'] = strtr('Running batch "@id" for @entity_type @bundle (@current of @total).', [
      '@id' => $batch_id,
      '@entity_type' => $entity_type,
      '@bundle' => $bundle,
      '@total' => $total,
      '@current' => $current,
    ]);
  }

  /**
   * Batch Finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post processing.
   * @param array $operations
   *   Array of operations.
   */
  public function processItemFinished($success, array $results, array $operations) {
    if ($success) {
      $repository = CsDemoRepository::getInstance();
      $repository->clearCaches();
    }
  }

}
