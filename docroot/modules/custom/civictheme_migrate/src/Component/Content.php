<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Class Content.
 *
 * Represents a Content component.
 */
class Content extends AbstractCivicThemeComponent {

  /**
   * {@inheritdoc}
   */
  public static function getSrcName(): string {
    return 'text_content';
  }

  /**
   * {@inheritdoc}
   */
  public static function getSrcFields(): array {
    return ['value'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareData($data, array $context): array {
    $data['content'] = $data['value'];

    return $data;
  }

}
