<?php

namespace Drupal\civictheme_migrate\Converter;

use Drupal\civictheme_migrate\LookupManager;
use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;

/**
 * Class AbstractEmbedConverter.
 *
 * Converts DOM elements to Drupal embed code.
 *
 * Implementing classes define the tags and lookups.
 */
abstract class AbstractEmbedConverter implements ConverterInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\civictheme_migrate\LookupManager
   */
  protected $entityManager;

  /**
   * A list of messages encountered during conversion.
   *
   * @var array
   */
  protected $messages = [];

  /**
   * A list of domains that should be considered local.
   *
   * @var array
   */
  protected $localDomains = [];

  /**
   * Constructor.
   *
   * @param \Drupal\civictheme_migrate\LookupManager $entity_manager
   *   The entity manager.
   * @param array $options
   *   An array of options.
   */
  public function __construct(LookupManager $entity_manager, array $options = []) {
    $this->entityManager = $entity_manager;
    $this->localDomains = $options['local_domains'] ?? [];
  }

  /**
   * Get the list of errors encountered during conversion.
   *
   * @return array
   *   The list of errors encountered during conversion.
   */
  public function getMessages() {
    return $this->messages;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(mixed $value): mixed {
    if (!is_string($value)) {
      return $value;
    }

    $dom = Html::load($value);

    if (!$dom) {
      return $value;
    }

    foreach (static::getTags() as $tag) {
      $elements = $dom->getElementsByTagName($tag);
      if ($elements) {
        // Due to the \DOMNodeList object being live, the replacements of its
        // elements may affect the objects within iterator, so we are iterating
        // backwards.
        /** @var \DOMElement $element */
        for ($i = $elements->length - 1; $i >= 0; $i--) {
          $element = $elements->item($i);

          $src = $this->getUrl($element);
          if (!$src) {
            continue;
          }

          $entity = $this->lookup($src);

          if (!$entity) {
            $this->messages[] = sprintf('Embed converter: entity with source URI %s was not found.', $src);
            continue;
          }

          static::updateDomElement($element, $entity);
        }
      }
    }

    return Html::serialize($dom);
  }

  /**
   * Get the tags to convert.
   *
   * Implementing classes should return an array of tag names that the converter
   * should be run on.
   *
   * @return array
   *   The tags to convert.
   */
  abstract protected static function getTags(): array;

  /**
   * Get the URL from the DOM element.
   *
   * Implementing classes should define how to extract the URL from the DOM
   * element.
   *
   * @param \DOMElement $element
   *   The element to extract the URL from.
   *
   * @return string|null
   *   The URL or NULL if not found.
   */
  abstract protected function getUrl(\DOMElement $element): ?string;

  /**
   * Lookup entity by source.
   *
   * @param string $src
   *   The source to lookup.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The entity or NULL if not found.
   */
  abstract protected function lookup(string $src): ?EntityInterface;

  /**
   * Update DOM element with the entity URL and embed attributes.
   *
   * @param \DOMElement $element
   *   The element to update.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity passed for context.
   */
  protected static function updateDomElement(\DOMElement $element, EntityInterface $entity): void {
    $element->setAttribute('href', $entity->toUrl()->toString());
    static::updateDomElementAttributes($element, $entity);
  }

  /**
   * Update attributes of the DOM element.
   *
   * @param \DOMElement $element
   *   The element to update.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity passed for context.
   */
  protected static function updateDomElementAttributes(\DOMElement $element, EntityInterface $entity): void {
    $element->setAttribute('data-entity-type', $entity->getEntityTypeId());
    $element->setAttribute('data-entity-uuid', $entity->uuid());
    $element->setAttribute('data-entity-substitution', 'canonical');
  }

}
