<?php

namespace Drupal\cs_demo\Commands;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Commands\DrushCommands;
use Psr\Log\LoggerInterface;

/**
 * A Drush command file for cs_demo module.
 */
class CsDemoCommands extends DrushCommands {

  use StringTranslationTrait;

  /**
   * Entity type service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  private $loggerChannelFactory;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new UpdateVideosStatsController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   Logger service.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactoryInterface $loggerChannelFactory, LoggerInterface $logger) {
    parent::__construct();
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerChannelFactory = $loggerChannelFactory;
    $this->logger = $logger;
  }

  /**
   * Create demo content.
   *
   * @param string $entity_type
   *   Entity type.
   * @param string $bundle
   *   Entity bundle.
   * @param int $total
   *   Number of items to create.
   *
   * @command cs-demo:create-content
   *
   * @usage drush cs-demo:create-content entity_type bundle count
   */
  public function createContent($entity_type, $bundle, $total) {
    // 1. Log the start of the script.
    $this->loggerChannelFactory->get('cs_demo')->info($this->t('Update nodes batch operations start'));

    $batchBuilder = new BatchBuilder();
    $batch_id = 1;

    for ($count = 0; $count < $total;) {
      $count += 50;
      $batchBuilder->addOperation('\Drupal\cs_demo\CsDemoBatchService::processItem', [
        $batch_id,
        $entity_type,
        $bundle,
        $total,
        $count,
      ]);
      $batch_id++;
    }

    $batchBuilder
      ->setTitle($this->t('Creating demo content for @entity_type @bundle (@total items in @batches batches)', [
        '@entity_type' => $entity_type,
        '@bundle' => $bundle,
        '@total' => $total,
        '@batches' => $batch_id,
      ]))
      ->setFinishCallback('\Drupal\cs_demo\CsDemoBatchService::processItemFinished')
      ->setErrorMessage($this->t('Batch has encountered an error'));

    batch_set($batchBuilder->toArray());
    drush_backend_batch_process();

    $this->logger->notice($this->t('Batch operations end.'));
  }

}
