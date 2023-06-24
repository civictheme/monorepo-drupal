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
   * The list of converters.
   *
   * @var array
   */
  protected $converters = [];

  /**
   * The list of converter class locations.
   *
   * @var array
   */
  protected $autodiscoveryLocations = [__DIR__];

  /**
   * Constructor.
   */
  public function __construct(protected LookupManager $lookupManager) {
  }

  /**
   * Get the list of autodiscovery locations.
   *
   * @return array
   *   The list of autodiscovery locations.
   */
  public function getAutodiscoveryLocations() {
    return $this->autodiscoveryLocations;
  }

  /**
   * Set the list of autodiscovery locations.
   *
   * @param array $autodiscovery_locations
   *   The list of autodiscovery locations.
   *
   * @return ConverterManager
   *   The converter manager.
   */
  public function setAutodiscoveryLocations(array $autodiscovery_locations) {
    $this->autodiscoveryLocations = $autodiscovery_locations;

    return $this;
  }

  /**
   * Add a converter.
   *
   * @param \Drupal\civictheme_migrate\Converter\ConverterInterface $converter
   *   The converter to add.
   *
   * @return ConverterManager
   *   The converter manager.
   */
  public function addConverter(ConverterInterface $converter) {
    $this->converters[$converter::name()] = $converter;

    return $this;
  }

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
  protected function converters($skip_autodiscovery = FALSE) {
    $converters = $this->converters;

    if (!$skip_autodiscovery) {
      $converters += $this->discoverConverters();
    }

    usort($converters, function ($a, $b) {
      return $a::weight() <=> $b::weight();
    });

    $converters = array_filter($converters, function ($converter) {
      return !in_array($converter::name(), $this->excludeConverters);
    });

    return $converters;
  }

  /**
   * Discover converters.
   *
   * @return \Drupal\civictheme_migrate\Converter\ConverterInterface[]
   *   The list of discovered converters.
   */
  protected function discoverConverters() {
    $converters = [];

    $converter_classes = [];
    foreach ($this->getAutodiscoveryLocations() as $location) {
      $converter_classes = Utility::loadClasses($location, AbstractEmbedConverter::class);
    }

    foreach ($converter_classes as $converter_class) {
      $converters[] = new $converter_class($this->lookupManager);
    }

    return $converters;
  }

}
