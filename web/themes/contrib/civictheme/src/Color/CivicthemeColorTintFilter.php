<?php

declare(strict_types=1);

namespace Drupal\civictheme\Color;

/**
 * Tint color filter.
 */
class CivicthemeColorTintFilter extends CivicthemeColorFilterBase {

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return 'tint';
  }

  /**
   * {@inheritdoc}
   */
  protected function expectedArgumentsCount(): int {
    return 1;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function filter(string $color): string {
    return CivicthemeColorUtility::mix($color, '#fff', $this->arguments[0]);
  }

}
