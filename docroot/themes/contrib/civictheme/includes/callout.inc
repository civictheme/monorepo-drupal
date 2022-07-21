<?php

/**
 * @file
 * CivicTheme Callout paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_callout(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $paragraph_links = civictheme_get_field_value($paragraph, 'field_c_p_links') ?? [];
  $links = [];
  foreach ($paragraph_links as $paragraph_link) {
    $links[] = [
      'url' => $paragraph_link->getUrl()->toString(),
      'text' => $paragraph_link->title,
      'type' => 'primary',
      'size' => 'regular',
      'is_external' => $paragraph_link->getUrl()->isExternal(),
    ];
  }

  $variables['links'] = $links;
}
