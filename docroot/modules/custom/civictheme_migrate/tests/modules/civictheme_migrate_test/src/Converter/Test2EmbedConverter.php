<?php

namespace Drupal\civictheme_migrate_test\Converter;

use Drupal\civictheme_migrate\Converter\AbstractEmbedConverter;
use Drupal\Core\Entity\EntityInterface;

/**
 * Class Test2EmbedConverter.
 *
 * Used in testing.
 */
class Test2EmbedConverter extends AbstractEmbedConverter {

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return 'test2';
  }

  /**
   * {@inheritdoc}
   */
  public static function weight(): int {
    return 20;
  }

  /**
   * {@inheritdoc}
   */
  protected static function getTags(): array {
    return ['a'];
  }

  /**
   * {@inheritdoc}
   */
  protected function lookup($src): ?EntityInterface {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrl(\DOMElement $element): ?string {
    return NULL;
  }

}
