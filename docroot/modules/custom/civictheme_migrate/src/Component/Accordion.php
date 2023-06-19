<?php

namespace Drupal\civictheme_migrate\Component;

use Drupal\Component\Utility\NestedArray;

/**
 * Attachment component.
 *
 * Represents an Attachment component.
 */
class Accordion extends AbstractCivicThemeComponent {

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
    if (!empty($data['children']['accordion_list']['children'])) {
      $panels = [];
      foreach ($data['children']['accordion_list']['children'] as $items) {
        $children = NestedArray::getValue($items, [
          'accordion_items',
          'children',
        ]);
        foreach ($children as $child) {
          if (empty($child['item_title']) || empty($child['item_content']['value'])) {
            continue;
          }
          $panels[] = [
            'title' => $child['item_title'],
            'content' => $child['item_content']['value'],
          ];
        }
      }

      $data['panels'] = $panels;
    }

    return $data;
  }

}
