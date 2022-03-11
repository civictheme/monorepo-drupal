<?php

/**
 * @file
 * Civic Accordion paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;

/**
 * Implements template_preprocess_paragraph().
 */
function civic_preprocess_paragraph__civic_map(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $variables['embed_url'] = $paragraph->get('field_c_p_embed_url')->getString();
  $variables['address'] = $paragraph->get('field_c_p_address')->getString();
  if (!$paragraph->get('field_c_p_share_link')->isEmpty()) {
    $link = $paragraph->get('field_c_p_share_link');
    $variables['share_link'] = $link->getString();
    $variables['share_link_is_external'] = UrlHelper::isExternal($link->first()->getValue()['uri']);
  }
  if (!$paragraph->get('field_c_p_view_link')->isEmpty()) {
    $link = $paragraph->get('field_c_p_view_link');
    $variables['view_link'] = $link->getString();
    $variables['view_link_is_external'] = UrlHelper::isExternal($link->first()->getValue()['uri']);
  }
  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
}
