<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\civictheme_migrate\Component\Field\BackgroundFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ContentFieldTrait;
use Drupal\civictheme_migrate\Component\Field\ThemeFieldTrait;
use Drupal\civictheme_migrate\Component\Field\TitleFieldTrait;
use Drupal\civictheme_migrate\Component\Field\VerticalSpacingFieldTrait;
use Drupal\Component\Utility\NestedArray;

/**
 * Class Attachment.
 *
 * Represents an Attachment component.
 */
class Attachment extends AbstractCivicThemeComponent {

  use BackgroundFieldTrait;
  use ContentFieldTrait;
  use ThemeFieldTrait;
  use TitleFieldTrait;
  use VerticalSpacingFieldTrait;

  /**
   * A list of attachments.
   *
   * @var array
   */
  protected $attachments = [];

  /**
   * Get the attachments.
   *
   * @return array
   *   A list of attachments.
   */
  public function getAttachments():array {
    return $this->attachments;
  }

  /**
   * Set the attachments.
   *
   * @param array $value
   *   The attachments.
   */
  public function setAttachments(array $value): void {
    $this->attachments = $value;
  }

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
    $data = $data['children'];

    if (!empty($data['attachments']['children'])) {
      $attachments = [];

      $media_items = NestedArray::getValue($data, [
        'attachments',
        'children',
      ]);

      foreach ($media_items as $media_item) {
        $media = $this->migrateLookup->lookup('media_civictheme_document', [$media_item]);
        if (!empty($media)) {
          $attachments[] = $media[0]['mid'];
        }
      }
      $data['attachments'] = $attachments;
    }

    return $data;
  }

}
