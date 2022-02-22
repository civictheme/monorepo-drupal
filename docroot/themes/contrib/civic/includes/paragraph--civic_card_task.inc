<?php

/**
 * @file
 * Civic card task paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_card_task(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['size'] = 'small';

  // Added to render image as icon.
  if ($paragraph->hasField('field_c_p_icon') && !$paragraph->get('field_c_p_icon')->isEmpty()) {
    $variables['image_as_icon'] = TRUE;
  }
}
