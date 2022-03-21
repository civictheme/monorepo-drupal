<?php

/**
 * @file
 * Civic Slider paragraph component.
 */

use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 */
function civic_preprocess_paragraph__civic_slider(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  _civic_preprocess_paragraph__field__theme($variables);
  _civic_preprocess_paragraph__field__title($variables);

  $variables['previous_label'] = t('Previous');
  $variables['next_label'] = t('Next');

  // Link.
  if ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) {
    $link = $paragraph->get('field_c_p_link')->first();
    $variables['link'] = [
      'type' => 'secondary',
      'size' => 'regular',
      'text' => $link->get('title')->getString(),
      'url' => Url::fromUri($link->get('uri')->getString()),
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
function civic_preprocess_paragraph__civic_slider_slide(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  _civic_preprocess_paragraph__field__theme($variables);
  _civic_preprocess_paragraph__field__title($variables);
  _civic_preprocess_paragraph__card__image($variables);

  $variables['attributes']['data-component-civic-slider-slide'] = '';

  // Image position.
  if ($paragraph->hasField('field_c_p_image_position') && !$paragraph->get('field_c_p_image_position')->isEmpty()) {
    $variables['image_position'] = $paragraph->get('field_c_p_image_position')->getString();
  }

  // Topic.
  if ($paragraph->hasField('field_c_p_topic') && !$paragraph->get('field_c_p_topic')->isEmpty()) {
    $topics = [];
    foreach ($paragraph->get('field_c_p_topic')->referencedEntities() as $topic) {
      $topics[] = [
        'theme' => $variables['theme'],
        'text' => $topic->label(),
      ];
    }
    $variables['content_top']['slide_topics'] = [
      '#theme' => 'civic_slider_slide_tags',
      '#tags' => $topics,
    ];
  }

  // Summary.
  if ($paragraph->hasField('field_c_p_content') && !$paragraph->get('field_c_p_content')->isEmpty()) {
    $variables['summary'] = text_summary($paragraph->get('field_c_p_content')->getValue()[0]['value'], NULL, 100);
  }

  // Links.
  if ($paragraph->hasField('field_c_p_link') && !$paragraph->get('field_c_p_link')->isEmpty()) {
    $inverse_theme = $variables['theme'] === 'dark' ? 'light' : 'dark';
    $link = $paragraph->get('field_c_p_link')->first();
    $links = [];
    $links[] = [
      'type' => 'primary',
      'size' => 'regular',
      'text' => $link->get('title')->getString(),
      'url' => Url::fromUri($link->get('uri')->getString()),
    ];
    $variables['links']['slide_links'] = [
      '#theme' => 'civic_slider_slide_links',
      '#component_theme' => $inverse_theme,
      '#links' => $links,
    ];
  }
}
