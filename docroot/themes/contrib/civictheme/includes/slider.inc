<?php

/**
 * @file
 * CivicTheme Slider paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_slider(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  $variables['previous_label'] = t('Previous');
  $variables['next_label'] = t('Next');

  // Link.
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  if ($link) {
    $variables['link'] = [
      'type' => 'secondary',
      'size' => 'regular',
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_external' => $link->isExternal(),
    ];
  }

  // Slides.
  if (!empty($variables['content']['field_c_p_slides'])) {
    $variables['slides'] = \Drupal::service('renderer')->render($variables['content']['field_c_p_slides']);
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  _civictheme_preprocess_paragraph__field__image($variables);

  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');
  $variables['tags'] = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topics');
  $variables['summary'] = civictheme_get_field_value($paragraph, 'field_c_p_content', TRUE);

  $date = civictheme_get_field_value($paragraph, 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date->get('value')->getDateTime());
  }

  $links = civictheme_get_field_value($paragraph, 'field_c_p_links');
  if ($links) {
    foreach ($links as $link) {
      $variables['links'][] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
      ];
    }
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide_ref(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if ($paragraph->hasField('field_c_p_reference')) {
    $nodes = $paragraph->field_c_p_reference->referencedEntities();
    if (!empty($nodes)) {
      /** @var \Drupal\node\NodeInterface $node */
      $node = reset($nodes);

      $variables['title'] = $node->label();
      $variables['date'] = civictheme_format_date('civictheme_short_date', $node->getChangedTime());
      $variables['summary'] = civictheme_get_field_value($node, 'field_c_n_summary');
      $variables['tags'] = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');

      $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE);
      if ($media) {
        $variables['image'] = civictheme_media_image_get_variables($media);
        $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');
      }

      $link_text = civictheme_get_field_value($paragraph, 'field_c_p_link_text', TRUE);
      if ($link_text) {
        $variables['links'][] = [
          'text' => $link_text,
          'url' => $node->toUrl()->toString(),
          'is_new_window' => $node->toUrl()->isExternal(),
        ];
      }
    }
  }
}
