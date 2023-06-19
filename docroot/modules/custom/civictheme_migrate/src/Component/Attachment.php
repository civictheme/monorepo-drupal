<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\Component\Utility\NestedArray;

/**
 * Class Attachment.
 *
 * Represents an Attachment component.
 */
class Attachment extends AbstractCivicThemeComponent {

  /**
   * {@inheritdoc}
   */
  public static function getSrcFields(): array {
    return ['children'];
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareData($data, array $context): array {
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
