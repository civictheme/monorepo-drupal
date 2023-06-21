<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait VerticalSpacingFieldTrait.
 *
 * Provides a 'vertical_spacing' field.
 */
trait VerticalSpacingFieldTrait {

  use HelperFieldTrait;

  /**
   * Vertical spacing field.
   *
   * @var string
   */
  protected $verticalSpacing;

  /**
   * Set the vertical spacing.
   */
  public function setVerticalSpacing($value): void {
    $this->verticalSpacing = static::valueFromOptions(static::verticalSpacingTypes(), $value);
  }

  /**
   * Get the vertical spacing.
   */
  public function getVerticalSpacing(): string {
    return $this->verticalSpacing ?? static::verticalSpacingTypeNone();
  }

  /**
   * Spacing - none.
   */
  public static function verticalSpacingTypeNone(): string {
    return 'none';
  }

  /**
   * Spacing - top.
   */
  public static function verticalSpacingTypeTop(): string {
    return 'top';
  }

  /**
   * Spacing - bottom.
   */
  public static function verticalSpacingTypeBottom(): string {
    return 'bottom';
  }

  /**
   * Spacing - both.
   */
  public static function verticalSpacingTypeBoth(): string {
    return 'both';
  }

  /**
   * Types of spaces.
   */
  public static function verticalSpacingTypes(): array {
    return [
      static:: verticalSpacingTypeNone(),
      static:: verticalSpacingTypeTop(),
      static:: verticalSpacingTypeBottom(),
      static:: verticalSpacingTypeBoth(),
    ];
  }

}
