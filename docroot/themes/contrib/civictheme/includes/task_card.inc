<?php

/**
 * @file
 * CivicTheme Task card paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_task_card(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $link = civictheme_field_has_value($paragraph, 'field_c_p_link') ? $paragraph->get('field_c_p_link')->first()->getValue() : [];
  if (!empty($link['uri']) && !UrlHelper::isExternal($link['uri'])) {
    $link['uri'] = Url::fromUri($link['uri'])->toString();
  }
  $variables['url'] = $link['uri'] ?? NULL;
  $variables['size'] = 'small';

  // Added to render image as icon.
  if (civictheme_field_has_value($paragraph, 'field_c_p_icon')) {
    $variables['image_as_icon'] = TRUE;
  }
}
