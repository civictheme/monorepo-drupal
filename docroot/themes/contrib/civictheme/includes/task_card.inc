<?php

/**
 * @file
 * CivicTheme Task card paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_task_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['url'] = $link->getUrl()->toString();
    $variables['is_external'] = $link->isExternal();
  }
  $variables['size'] = 'small';

  // Added to render image as icon.
  if (civictheme_field_has_value($paragraph, 'field_c_p_icon')) {
    $variables['image_as_icon'] = TRUE;
  }
}
