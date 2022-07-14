<?php

/**
 * @file
 * CivicTheme Slider paragraph component.
 */

/**
 * Implements template_preprocess_paragraph().
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
  $theme = $variables['theme'];

  _civictheme_preprocess_paragraph__card__image($variables);

  $variables['attributes']['data-component-civictheme-slider-slide'] = '';

  // Image position.
  $variables['image_position'] = civictheme_get_field_value($paragraph, 'field_c_p_image_position');

  // Topic.
  if ($topics = civictheme_get_referenced_entity_labels($paragraph, 'field_c_p_topic')) {
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
  $parent = $paragraph->_referringItem->getEntity();
  // @todo add theme field to reference slide.
  $theme = civictheme_get_theme_value($parent);
  $paragraph->civic_theme = $theme;
}
