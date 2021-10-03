<?php

/**
 * @file
 * Civic Callout paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_callout(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_links') && !$paragraph->field_c_p_links->isEmpty()) {
    $links = [];
    foreach ($paragraph->field_c_p_links as $link) {
      if (!empty($link)) {
        $links[] = [
          'url' => $link->url,
          'text' => $link->title,
          'type' => 'primary',
          'size' => 'regular',
        ];
      }
    }

    $variables['links'] = $links;
  }
}
