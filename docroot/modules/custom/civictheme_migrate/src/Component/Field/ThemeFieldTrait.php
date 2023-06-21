<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait ThemeFieldTrait.
 *
 * Provides a 'theme' field.
 */
trait ThemeFieldTrait {

  use HelperFieldTrait;

  /**
   * Theme field.
   *
   * @var string
   */
  protected $theme;

  /**
   * Set the theme.
   */
  public function setTheme(string $value): void {
    $this->theme = static::valueFromOptions(static::themes(), $value);
  }

  /**
   * Get the theme.
   */
  public function getTheme(): string {
    return $this->theme ?? static::themeLight();
  }

  /**
   * Light theme name.
   */
  public static function themeLight(): string {
    return 'light';
  }

  /**
   * Dark theme name.
   */
  public static function themeDark(): string {
    return 'dark';
  }

  /**
   * Available theme names.
   */
  public static function themes(): array {
    return [
      static::themeLight(),
      static::themeDark(),
    ];
  }

}
