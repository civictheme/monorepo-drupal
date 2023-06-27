<?php

declare(strict_types=1);

namespace Drupal\civictheme_migrate\Plugin\migrate_plus\data_parser;

use Drupal\civictheme_migrate\Utility;
use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Json;

/**
 * Obtain JSON data for migration with support of wildcards in selectors.
 *
 * @DataParser(
 *   id = "jsonwildcard",
 *   title = @Translation("JSON")
 * )
 */
class JsonWildcard extends Json {

  /**
   * {@inheritdoc}
   */
  protected function fetchNextRow(): void {
    $current = $this->iterator->current();
    if ($current) {
      foreach ($this->fieldSelectors() as $field_name => $selector) {
        $this->currentItem[$field_name] = Utility::extractValueByPath($current, $selector);
      }

      if (!empty($this->configuration['include_raw_data'])) {
        $this->currentItem['raw'] = $current;
      }

      $this->iterator->next();
    }
  }

}
