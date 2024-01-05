<?php

declare(strict_types=1);

namespace Drupal\cs_generated_content;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class CsGeneratedContentServiceProvider.
 *
 * Asset generator service replacement with an extending class.
 *
 * @package Drupal\cs_generated_content
 */
class CsGeneratedContentServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container): void {
    $definition = $container->getDefinition('generated_content.asset_generator');
    $definition->setClass(CsGeneratedContentAssetGenerator::class);
  }

}
