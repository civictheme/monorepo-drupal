<?php

/**
 * @file
 * Civic Callout paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_callout(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_links') && !$paragraph->get('field_c_p_links')->isEmpty()) {
    $links = [];
    foreach ($paragraph->field_c_p_links as $link) {
      $link_uri = $link->uri;
      $is_external = UrlHelper::isExternal($link->uri);
      if (!$is_external) {
        $link_uri = Url::fromUri($link->uri)->toString();
      }
      $links[] = [
        'url' => $link_uri,
        'text' => $link->title,
        'type' => 'primary',
        'size' => 'regular',
      ];
    }

    $variables['links'] = $links;
  }
}
