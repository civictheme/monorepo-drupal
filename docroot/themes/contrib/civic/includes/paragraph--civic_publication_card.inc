<?php

/**
 * @file
 * Civic publication card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_publication_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['is_external'] = !empty($link['uri']) ? UrlHelper::isExternal($link['uri']) : FALSE;
  // Publication card size.
  if ($paragraph->hasField('field_c_p_size') && !$paragraph->get('field_c_p_size')->isEmpty()) {
    $variables['size'] = $paragraph->get('field_c_p_size')->getString();
  }

  if ($paragraph->hasField('field_c_p_document') && !$paragraph->get('field_c_p_document')->isEmpty()) {
    $documents = $paragraph->field_c_p_document->referencedEntities();
    $document = reset($documents);
    $fid = $document->getSource()->getSourceFieldValue($document);

    /** @var \Drupal\file\FileInterface $file */
    $file = $fid ? File::load($fid) : NULL;
    if (!empty($file)) {
      $url = $file->createFileUrl(FALSE);
      $file_size = format_size($file->getSize());
      $variables['link']['url'] = $url;
      $variables['link']['text'] = basename($url) . ' (' . $file_size . ')';
      $variables['url'] = $url;
    }
  }
}
