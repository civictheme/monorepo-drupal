<?php

/**
 * @file
 * CivicTheme card promo paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'] ?? NULL;
  if (!$paragraph) {
    return;
  }

  if ($paragraph) {
    /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
    $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
    if ($link) {
      $variables['url'] = $link->getUrl()->toString();
    }

    $variables['subtitle'] = civictheme_get_field_value($paragraph, 'field_c_p_subtitle', TRUE);
    $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  }
}
