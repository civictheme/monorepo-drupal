<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait BackgroundFieldTrait.
 *
 * Provides a 'background' field.
 */
trait BackgroundFieldTrait {

  /**
   * Whether to use background.
   *
   * @var bool
   */
  protected $background = FALSE;

  /**
   * Set the flag to use background.
   */
  public function setBackground(mixed $value): void {
    $this->background = (bool) $value;
  }

  /**
   * Get whether to use background.
   */
  public function getBackground():bool {
    return $this->background;
  }

}
