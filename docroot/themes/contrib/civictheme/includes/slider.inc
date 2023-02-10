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
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  // @todo Make these configurable in theme settings.
  $variables['previous_label'] = t('Previous');
  $variables['next_label'] = t('Next');

  if (!empty($variables['content']['field_c_p_slides'])) {
    $variables['slides'] = \Drupal::service('renderer')->render($variables['content']['field_c_p_slides']);
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide(&$variables) {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__image_position($variables);
  _civictheme_preprocess_paragraph__paragraph_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__date($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__links($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide_ref(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  if (!$paragraph->hasField('field_c_p_reference')) {
    return;
  }

  /** @var \Drupal\node\NodeInterface $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE);
  if (empty($node)) {
    return;
  }

  $variables['title'] = $node->label();
  $variables['date'] = civictheme_format_date('civictheme_short_date', $node->getChangedTime());
  $variables['content'] = civictheme_get_field_value($node, 'field_c_n_summary');
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
