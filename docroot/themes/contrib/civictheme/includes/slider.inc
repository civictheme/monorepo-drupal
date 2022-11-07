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
  $theme = civictheme_get_field_theme_value($paragraph, $variables['theme'] ?? NULL);

  _civictheme_preprocess_paragraph__card__image($variables);

  $variables['attributes']['data-component-ct-slider-slide'] = '';

  // Image position.
  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');

  // Topic.
  $topics = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topic');
  if ($topics) {
    $topics = array_map(function ($topic) use ($theme) {
      return [
        'text' => $topic,
        'theme' => $theme,
      ];
    }, $topics);

    $variables['content_top']['slide_topics'] = [
      '#theme' => 'civictheme_slider_slide_tags',
      '#tags' => $topics,
    ];
  }

  // Summary.
  $variables['summary'] = civictheme_get_field_value($paragraph, 'field_c_p_content', TRUE);

  // Links.
  $link = civictheme_get_field_value($paragraph, 'field_c_p_link', TRUE);
  $links = [];
  if ($link) {
    $links[] = [
      'type' => 'primary',
      'size' => 'regular',
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
    ];
    $variables['links']['slide_links'] = [
      '#theme' => 'civictheme_slider_slide_links',
      '#component_theme' => $theme,
      '#links' => $links,
    ];
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide_ref(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_theme_value($paragraph, $variables['theme'] ?? NULL);
  // Image position.
  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');
  $variables['attributes']['data-component-ct-slider-slide'] = '';

  if ($paragraph->hasField('field_c_p_reference')) {
    $node = $paragraph->field_c_p_reference->referencedEntities();
    if (!empty($node)) {
      /** @var \Drupal\node\NodeInterface $node */
      $node = reset($node);
      $variables['title'] = $node->label();

      // Image.
      if (civictheme_field_has_value($node, 'field_c_n_thumbnail')) {
        $target = $node->get('field_c_n_thumbnail')->first()->get('entity')->getTarget();
        if ($target) {
          $media = $target->getValue();
          $variables['image'] = civictheme_media_image_process_variables($media);
        }
      }

      // Topic.
      $topics = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');
      if ($topics) {
        $topics = array_map(function ($topic) use ($theme) {
          return [
            'text' => $topic,
            'theme' => $theme,
          ];

        }, $topics);
      }

      // Date.
      $date_range = civictheme_get_field_value($node, 'field_c_n_date', TRUE);
      if ($date_range) {
        $topics[] = [
          'text' => civictheme_format_short_date($date_range->get('value')->getString()),
          'theme' => $theme,
          'type' => 'tertiary',
          'modifier_class' => 'ct-slide__date',
        ];
      }

      if ($topics) {
        $variables['content_top']['slide_topics'] = [
          '#theme' => 'civictheme_slider_slide_tags',
          '#tags' => $topics,
        ];
      }

      // Summary.
      $variables['summary'] = civictheme_get_field_value($node, 'field_c_n_summary') ?? civictheme_get_field_value($node, 'field_c_n_tagline');

      // Links.
      $links = [];
      $links[] = [
        'type' => 'primary',
        'size' => 'regular',
        'text' => $node->label(),
        'url' => $node->toUrl()->toString(),
      ];
      $variables['links']['slide_links'] = [
        '#theme' => 'civictheme_slider_slide_links',
        '#component_theme' => $variables['theme'],
        '#links' => $links,
      ];
    }
  }
}
