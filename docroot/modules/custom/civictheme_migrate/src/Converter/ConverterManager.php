<?php

namespace Drupal\civictheme_migrate\Converter;

use Drupal\civictheme_migrate\LookupManager;
use Drupal\civictheme_migrate\Utility;

/**
 * Class ConverterManager.
 *
 * Manages converter loading and processing.
 */
class ConverterManager {

  /**
   * Array of converter names to be excluded.
   *
   * @var array
   */
  protected $excludeConverters = [];

  /**
   * A list of messages encountered during conversion.
   *
   * @var array
   */
  protected $messages = [];

  /**
   * Constructor.
   */
  public function __construct(protected LookupManager $lookupManager) {}

  /**
   * Get the list of messages encountered during conversion.
   */
  public function getMessages(): array {
    return $this->messages;
  }

  /**
   * Get the list of converter names to be excluded during processing.
   *
   * @return array
   *   The list of converter names to be excluded.
   */
  public function getExcludeConverters(): array {
    return $this->excludeConverters;
  }

  /**
   * Set the list of converter names to be excluded during processing.
   *
   * @param array $exclude_converters
   *   The list of converter names to be excluded.
   *
   * @return ConverterManager
   *   The converter manager.
   */
  public function setExcludeConverters(array $exclude_converters): ConverterManager {
    $this->excludeConverters = $exclude_converters;

    return $this;
  }

  /**
   * Convert a value using available converters.
   */
  public function convert(mixed $value): mixed {
    foreach ($this->converters() as $converter) {
      $value = $converter->convert($value);
      $this->messages = array_merge($this->messages, $converter->getMessages());
    }

    return $value;
  }

  /**
   * Get the list of converters.
   *
   * @return \Drupal\civictheme_migrate\Converter\ConverterInterface[]
   *   The list of instantiated converters.
   */
  protected function converters() {
    $converters = [];

    $converter_classes = Utility::loadClasses(__DIR__, AbstractEmbedConverter::class);

    usort($converter_classes, function ($a, $b) {
      return $a::weight() <=> $b::weight();
    });

    $converter_classes = array_filter($converter_classes, function ($class) {
      return !in_array($class::name(), $this->excludeConverters);
    });

    foreach ($converter_classes as $converter_class) {
      $converters[] = new $converter_class($this->lookupManager);
    }

    return $converters;
  }

}
