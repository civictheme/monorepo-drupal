<?php

/**
 * @file
 * CivicTheme promo paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['link'] = [
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_new_window' => $link->isExternal(),
    ];
  }
  $variables['content'] = civictheme_get_field_value($paragraph, 'field_c_p_summary');
}
