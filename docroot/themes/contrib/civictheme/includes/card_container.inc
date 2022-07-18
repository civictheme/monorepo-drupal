<?php

/**
 * @file
 * CivicTheme Card container paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_card_container(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  $variables['column_count'] = civictheme_get_field_value($paragraph, 'field_c_p_column_count') ?? '';
  $variables['fill_width'] = civictheme_get_field_value($paragraph, 'field_c_p_fill_width') ?? FALSE;

  $header_link = civictheme_get_field_value($paragraph, 'field_c_p_header_link', TRUE) ?? NULL;
  $variables['header_link_text'] = $header_link !== NULL ? $header_link->get('title')->getString() : NULL;
  $variables['header_link_url'] = $header_link !== NULL ? $header_link->getUrl()->toString() : NULL;

  $footer_link = civictheme_get_field_value($paragraph, 'field_c_p_footer_link', TRUE);
  $variables['footer_link_text'] = $footer_link !== NULL ? $footer_link->get('title')->getString() : NULL;
  $variables['footer_link_url'] = $footer_link !== NULL ? $footer_link->getUrl()->toString() : NULL;
  $variables['description'] = civictheme_get_field_value($paragraph, 'field_c_p_description') ?? '';

  $variables['cards'] = array_filter($variables['content']['field_c_p_cards'], function ($key) {
    return is_numeric($key);
  }, ARRAY_FILTER_USE_KEY);
}
