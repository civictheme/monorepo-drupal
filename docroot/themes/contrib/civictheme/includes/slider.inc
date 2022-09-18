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
    $inverse_theme = $theme === 'dark' ? 'light' : 'dark';
    $links[] = [
      'type' => 'primary',
      'size' => 'regular',
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
    ];
    $variables['links']['slide_links'] = [
      '#theme' => 'civictheme_slider_slide_links',
      '#component_theme' => $inverse_theme,
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

        $variables['content_top']['slide_topics'] = [
          '#theme' => 'civictheme_slider_slide_tags',
          '#tags' => $topics,
        ];
      }

      // Summary.
      $variables['summary'] = civictheme_get_field_value($node, 'field_c_n_summary') ?? civictheme_get_field_value($node, 'field_c_n_tagline');

      // Links.
      $inverse_theme = $variables['theme'] === 'dark' ? 'light' : 'dark';
      $links = [];
      $links[] = [
        'type' => 'primary',
        'size' => 'regular',
        'text' => $node->label(),
        'url' => $node->toUrl()->toString(),
      ];
      $variables['links']['slide_links'] = [
        '#theme' => 'civictheme_slider_slide_links',
        '#component_theme' => $inverse_theme,
        '#links' => $links,
      ];
    }
  }
}
