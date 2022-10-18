<?php

namespace Drupal\civictheme\Color;

/**
 * Shade color filter.
 */
class CivicthemeColorShadeFilter extends CivicthemeColorFilterBase {

  /**
   * {@inheritdoc}
   */
  public static function name() {
    return 'shade';
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
    return CivicthemeColorUtility::mix($color, '#000', $this->arguments[0]);
  }

}
