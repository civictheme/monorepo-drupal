<?php

namespace Drupal\civictheme\Color;

/**
 * Tint color filter.
 */
class CivicthemeColorTintFilter extends CivicthemeColorFilterBase {

  /**
   * {@inheritdoc}
   */
  public static function name() {
    return 'tint';
  }

  /**
   * {@inheritdoc}
   */
  protected function expectedArgumentsCount() {
    return 1;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(StaticAccess)
   */
  public function filter($color) {
    return CivicthemeColorUtility::mix($color, '#fff', $this->arguments[0]);
  }

}
