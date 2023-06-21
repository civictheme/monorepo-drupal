<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait ContentFieldTrait.
 *
 * Provides a 'content' field.
 */
trait ContentFieldTrait {

  /**
   * Content.
   *
   * @var string
   */
  protected $content = '';

  /**
   * Set the content.
   */
  public function setContent(string|array $value): void {
    $value = $this->preprocessContent($value);
    $this->content = $value;
  }

  /**
   * Get the content.
   */
  public function getContent(): mixed {
    return $this->content;
  }

}
