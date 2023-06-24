<?php

namespace Drupal\civictheme_migrate\Component\Field;

/**
 * Trait HelperFieldTrait.
 *
 * Provides helping methods for other field traits.
 */
trait HelperFieldTrait {

  /**
   * Check that value exists in the provided options or return the first option.
   */
  protected static function valueFromOptions(array $options, $value) {
    return in_array($value, $options) ? $value : reset($options);
  }

  /**
   * Preprocess content.
   *
   * @param mixed $value
   *   The value to preprocess.
   *
   * @return mixed
   *   The preprocessed value.
   */
  protected function preprocessContent(mixed $value): array {
    $value = is_array($value) ? $value : ['value' => $value];

    $value += [
      'value' => '',
      'format' => 'civictheme_rich_text',
    ];

    $value['value'] = $this->converterManager->convert($value['value']);

    return $value;
  }

}
