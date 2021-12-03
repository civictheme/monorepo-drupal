<?php

/**
 * @file
 * Civic Slider paragraph component.
 */

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_slider(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $slider_title = $paragraph->get('field_c_p_title')->first();
  $variables['title'] = !empty($slider_title) ? $slider_title->getString() : NULL;
  $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
  $link = $paragraph->get('field_c_p_link')->first();
  $variables['link'] = [
    'type' => 'primary',
    'size' => 'regular',
    'text' => $link->get('title')->getString(),
    'url' => Url::fromUri($link->get('uri')->getString()),
  ];
  $variables['slides'] = \Drupal::service('renderer')->render($variables['content']['field_c_p_slides']);
  $variables['previous_label'] = t('Previous');
  $variables['next_label'] = t('Next');
}

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD)
 */
function civic_preprocess_paragraph__civic_slider_slide(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  // Theme.
  if ($paragraph->hasField('field_c_p_theme') && !$paragraph->get('field_c_p_theme')->isEmpty()) {
    $variables['theme'] = $paragraph->get('field_c_p_theme')->getString();
  }
  // Image position.
  if ($paragraph->hasField('field_c_p_image_position') && !$paragraph->get('field_c_p_image_position')->isEmpty()) {
    $variables['image_position'] = $paragraph->get('field_c_p_image_position')->getString();
  }
  // Title.
  if ($paragraph->hasField('field_c_p_title') && !$paragraph->get('field_c_p_title')->isEmpty()) {
    $variables['title'] = $paragraph->get('field_c_p_title')->first()->getString();
  }
  // Topic. - how do we render this out? Card Event has it hard-coded in the civic template.
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
    if ($variables['theme'] === 'dark') {
      $inverse_theme = 'light';
    } else {
      $inverse_theme = 'dark';
    }
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
  // Image.
  if ($paragraph->hasField('field_c_p_image') && !$paragraph->get('field_c_p_image')->isEmpty()) {
    $media = $paragraph->get('field_c_p_image')->first()->get('entity')->getTarget()->getValue();
    $variables['image'] = _civic_media_image($media);
  }
  $variables['attributes']['data-component-civic-slider-slide'] = '';
}
