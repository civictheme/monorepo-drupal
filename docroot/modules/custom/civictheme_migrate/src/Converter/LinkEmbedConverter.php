<?php

namespace Drupal\civictheme_migrate\Converter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\media\MediaInterface;

/**
 * Class LinkEmbedConverter.
 *
 * Converts DOM elements to Drupal link embed code.
 */
class LinkEmbedConverter extends AbstractEmbedConverter {

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return 'link_embed';
  }

  /**
   * {@inheritdoc}
   */
  public static function weight(): int {
    // Run after all other converters as link may wrap other entities.
    return 100;
  }

  /**
   * {@inheritdoc}
   */
  protected static function getTags(): array {
    return ['a'];
  }

  /**
   * {@inheritdoc}
   */
  protected function lookup($src): ?EntityInterface {
    $entity = $this->entityManager->lookupMediaByFileName(basename($src));

    if (!$entity) {
      $entity = $this->entityManager->lookupNodeByAlias($src);
    }

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrl(\DOMElement $element): ?string {
    return in_array(strtolower($element->tagName), static::getTags()) && $element->hasAttribute('href')
      ? UrlHelper::extractLocalUrl($element->getAttribute('href'), $this->localDomains)
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected static function updateDomElementAttributes(\DOMElement $element, EntityInterface $entity): void {
    parent::updateDomElementAttributes($element, $entity);
    // Render media as a link to the file.
    if ($entity instanceof MediaInterface) {
      $element->setAttribute('data-entity-substitution', 'media');
    }
  }

}
