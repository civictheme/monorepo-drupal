<?php

namespace Drupal\civictheme_migrate\Component;

/**
 * Abstract class AbstractCivicThemeComponent.
 *
 * Represents a CivicTheme component.
 */
abstract class AbstractCivicThemeComponent extends AbstractComponent {

  use CivicthemeTrait;

  /**
   * {@inheritdoc}
   */
  protected function build($name, $options): mixed {
    return self::civicthemeComponentCreate($name, $options);
  }

}
