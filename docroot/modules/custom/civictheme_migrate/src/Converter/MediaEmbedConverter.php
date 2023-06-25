<?php

namespace Drupal\civictheme_migrate\Converter;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class MediaEmbedConverter.
 *
 * Converts DOM elements to Drupal media embed code.
 */
class MediaEmbedConverter extends AbstractEmbedConverter {

  /**
   * {@inheritdoc}
   */
  public static function name(): string {
    return 'media_embed';
  }

  /**
   * {@inheritdoc}
   */
  public static function weight(): int {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  protected static function getTags(): array {
    return ['img', 'video'];
  }

  /**
   * {@inheritdoc}
   */
  protected function lookup($src): ?EntityInterface {
    return $this->entityManager->lookupMediaByFileName(basename($src));
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrl(\DOMElement $element): ?string {
    return in_array(strtolower($element->tagName), static::getTags()) && $element->hasAttribute('src')
      ? static::extractUrlFromDomElement($element, 'src')
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected static function updateDomElement(\DOMElement $element, EntityInterface $entity): void {
    $replacement = $element->ownerDocument->createElement('drupal-media');
    static::updateDomElementAttributes($replacement, $entity);
    if ($element->hasAttribute('alt')) {
      $replacement->setAttribute('alt', $element->getAttribute('alt'));
    }
    $element->parentNode->replaceChild($replacement, $element);
  }

}
