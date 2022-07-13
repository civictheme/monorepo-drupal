<?php

/**
 * @file
 * CivicTheme Accordion paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_map(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['url'] = $paragraph->get('field_c_p_embed_url')->getString();
  $variables['address'] = $paragraph->get('field_c_p_address')->getString();
  if (civictheme_field_has_value($paragraph, 'field_c_p_share_link')) {
    $link = $paragraph->get('field_c_p_share_link');
    $variables['share_link'] = $link->getString();
    $variables['share_link_is_external'] = UrlHelper::isExternal($link->first()->getValue()['uri']);
  }
  if (civictheme_field_has_value($paragraph, 'field_c_p_view_link')) {
    $link = $paragraph->get('field_c_p_view_link');
    $variables['view_link'] = $link->getString();
    $variables['view_link_is_external'] = UrlHelper::isExternal($link->first()->getValue()['uri']);
  }
  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
}
