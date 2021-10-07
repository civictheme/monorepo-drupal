<?php

/**
 * @file
 * Civic Attachment paragraph component.
 */

use Drupal\file\Entity\File;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_attachment(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_attachments') && !$paragraph->get('field_c_p_attachments')->isEmpty()) {
    $attachments = [];
    foreach ($paragraph->field_c_p_attachments->referencedEntities() as $attachment) {
      $fid = $attachment->getSource()->getSourceFieldValue($attachment);
      /** @var \Drupal\file\FileInterface $file */
      $file = $fid ? File::load($fid) : NULL;
      if (!empty($file)) {
        $url = $file->createFileUrl(FALSE);
        $file_size = format_size($file->getSize());
        $file_extension = pathinfo($file->getFileUri(), PATHINFO_EXTENSION);
        $attachments[] = [
          'url' => $url,
          'title' => t('@name <span class="extension">(@ext, @size)</span>', [
            '@name' => $file->label(),
            '@ext' => strtoupper($file_extension),
            '@size' => strtoupper($file_size),
          ]),
          'last_updated' => \Drupal::service('date.formatter')->format($attachment->getChangedTime(), 'civic_short_date'),
        ];
      }
    }

    $variables['links'] = $attachments;
  }
}
