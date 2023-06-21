<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait TitleFieldTrait.
 *
 * Provides a 'title' field.
 */
trait TitleFieldTrait {

  /**
   * Title field.
   *
   * @var string
   */
  protected $title = '';

  /**
   * Set the title.
   */
  public function setTitle($value): void {
    $this->title = $value;
  }

  /**
   * Get the title.
   */
  public function getTitle(): string {
    return $this->title;
  }

}
