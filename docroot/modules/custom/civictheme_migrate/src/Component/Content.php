<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Component\Field\BackgroundFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ContentFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ThemeFieldTrait;
use Drupal\civictheme_migrate\Component\Field\VerticalSpacingFieldTrait;

/**
 * Class Content.
 *
 * Represents a Content component.
 */
class Content extends AbstractCivicThemeComponent {

  use BackgroundFieldTrait;
  use ContentFieldTrait;
  use ThemeFieldTrait;
  use VerticalSpacingFieldTrait;

  /**
   * {@inheritdoc}
   */
  public static function migrateName(): string {
    return 'text_content';
  }

  /**
   * {@inheritdoc}
   */
  public static function migrateFields(): array {
    return ['value'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareStub($data, array $context): array {
    $data['content'] = $data['value'];

    return $data;
  }

}
