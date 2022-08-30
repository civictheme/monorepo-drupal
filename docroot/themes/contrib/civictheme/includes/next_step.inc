<?php

/**
 * @file
 * CivicTheme Next step paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_next_step(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|NULL $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['is_external'] = $link->isExternal();
  }
}
