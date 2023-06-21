<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait LinkFieldTrait.
 *
 * Provides a 'link' field.
 */
trait LinkFieldTrait {

  /**
   * Link field.
   *
   * @var string
   */
  protected $link = FALSE;

  /**
   * Set the link.
   */
  public function setLink($value): void {
    $this->link = $value;
  }

  /**
   * Get the link.
   */
  public function getLink(): mixed {
    return $this->link;
  }

}
