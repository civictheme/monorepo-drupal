<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Class Promo.
 *
 * Represents a Promo component.
 */
class Promo extends AbstractCivicThemeComponent {

  /**
   * {@inheritdoc}
   */
  public static function getSrcFields(): array {
    return ['children'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareData($data, array $context): array {
    return $data['children'];
  }

}
