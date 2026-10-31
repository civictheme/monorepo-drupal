<?php

declare(strict_types=1);

namespace Drupal\civictheme\Color;

/**
 * Shade color filter.
 */
class CivicthemeColorShadeFilter extends CivicthemeColorFilterBase {

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return 'shade';
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
    return CivicthemeColorUtility::mix($color, '#000', $this->arguments[0]);
  }

}
