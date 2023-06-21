<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Component\Field\ContentFieldTrait;
use Drupal\civictheme_migrate\Component\Field\LinkFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ThemeFieldTrait;
use Drupal\civictheme_migrate\Component\Field\TitleFieldTrait;
use Drupal\civictheme_migrate\Component\Field\VerticalSpacingFieldTrait;

/**
 * Class Promo.
 *
 * Represents a Promo component.
 */
class Promo extends AbstractCivicThemeComponent {

  use ContentFieldTrait;
  use LinkFieldTrait;
  use ThemeFieldTrait;
  use TitleFieldTrait;
  use VerticalSpacingFieldTrait;

  /**
   * {@inheritdoc}
   */
  public static function migrateFields(): array {
    return ['children'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareStub($data, array $context): array {
    return $data['children'];
  }

}
