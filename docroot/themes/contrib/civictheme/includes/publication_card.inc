<?php

/**
 * @file
 * CivicTheme publication card paragraph component.
 */

use Drupal\file\Entity\File;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_publication_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['is_external'] = $link->isExternal();
  }

  // Publication card size.
  $variables['size'] = civictheme_get_field_value($paragraph, 'field_c_p_size');
  /** @var \Drupal\media\MediaInterface|null $document $document */
  $document = civictheme_get_field_value($paragraph, 'field_c_p_document', TRUE);
  if ($document) {
    $fid = $document->getSource()->getSourceFieldValue($document);

    /** @var \Drupal\file\FileInterface $file */
    $file = $fid ? File::load($fid) : NULL;
    if (!empty($file)) {
      $url = file_create_url($file->createFileUrl());
      if ($url) {
        $file_size = format_size($file->getSize());
        $variables['link']['url'] = $url;
        $variables['link']['text'] = sprintf('%s (%s)', $file->getFilename(), $file_size);
        $variables['url'] = $url;
      }
    }
  }
}
