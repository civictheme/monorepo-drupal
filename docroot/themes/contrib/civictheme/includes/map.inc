<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_map(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link_embed_url = civictheme_get_field_value($paragraph, 'field_c_p_embed_url', TRUE);
  $variables['url'] = $link_embed_url->getUrl()->toString();
  $variables['address'] = civictheme_get_field_value($paragraph, 'field_c_p_address');
  $link_list = [
    'view_link' => 'field_c_p_view_link',
  ];
  foreach ($link_list as $key => $field_name) {
    $link = civictheme_get_field_value($paragraph, $field_name, TRUE);
    if ($link) {
      $variables[$key] = $link->getUrl()->toString();
      $variables[$key . '_is_external'] = $link->isExternal();
    }
  }
}
