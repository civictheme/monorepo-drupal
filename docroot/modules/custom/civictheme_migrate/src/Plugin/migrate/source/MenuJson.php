<?php

namespace Drupal\civictheme_migrate\Plugin\migrate\source;

use Drupal\migrate_plus\Plugin\migrate\source\Url;
use Drupal\migrate_plus\DataParserPluginInterface;

/**
 * Source plugin for retrieving data via URLs.
 *
 * @MigrateSource(
 *   id = "menu_json"
 * )
 */
class MenuJson extends Url {

  /**
   * Creates and returns a filtered Iterator over the documents.
   *
   *   An iterator over the documents providing source rows that match the
   *   configured item_selector.
   */
  protected function initializeIterator(): DataParserPluginInterface {
    $items = $this->getDataParserPlugin();

    print_r($items->iterator);
    die;
    if (isset($items->iterator)) {
      $flats = $this->flatternJson($items->iterator);
      $id = array_column($flats, 'id');
      array_multisort($id, SORT_ASC, $flats);
      $items->iterator = new \ArrayIterator($flats);
    }



    return $items;
  }

  /**
   * Provide flatterd json array.
   */
  private function flatternJson($items, $parent = 0, &$flatterdItems = [], &$id = 1) {
    foreach ($items as $item) {
      print_r($item);
      $item['parent'] = $parent;
      $item['id'] = $id++;
      if (isset($item['children'])) {
        $this->flatternJson($item['children'], $item['id'], $flatterdItems, $id);
        unset($item['children']);
      }

      $flatterdItems[] = $item;
    }

    return $flatterdItems;
  }

}
