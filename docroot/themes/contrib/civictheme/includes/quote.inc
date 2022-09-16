<?php

/**
 * @file
 * CivicTheme Quote paragraph component.
 */

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_quote(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if (civictheme_field_has_value($paragraph, 'field_c_p_author')) {
    $variables['author'] = civictheme_get_field_value($paragraph, 'field_c_p_author');
  }
}
