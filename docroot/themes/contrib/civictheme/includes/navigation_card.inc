<?php

/**
 * @file
 * CivicTheme navigation card paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|NULL $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['is_external'] = $link->isExternal();
  }

  // Navigation card size.
  $variables['size'] = civictheme_get_field_value($paragraph, 'field_c_p_size');
}
