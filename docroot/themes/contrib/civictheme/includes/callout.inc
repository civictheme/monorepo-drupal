<?php

/**
 * @file
 * CivicTheme Callout paragraph component.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_callout(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if (civictheme_field_has_value($paragraph, 'field_c_p_links')) {
    $links = [];
    foreach ($paragraph->field_c_p_links as $link) {
      if (empty($link)) {
        continue;
      }
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
