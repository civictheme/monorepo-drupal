<?php

/**
 * @file
 * CivicTheme Slider paragraph component.
 */

declare(strict_types=1);

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_slider(array &$variables): void {
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
function civictheme_preprocess_paragraph__civictheme_slider_slide(array &$variables): void {
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
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function civictheme_preprocess_paragraph__civictheme_slider_slide_ref(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  /** @var \Drupal\node\NodeInterface|null $node */
  $node = civictheme_get_field_value($paragraph, 'field_c_p_reference', TRUE);
  if (empty($node)) {
    return;
  }

  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__image_position($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);

  if ($node->bundle() == 'civictheme_event') {
    _civictheme_preprocess_paragraph__node_field__date_range($variables);
  }
  else {
    _civictheme_preprocess_paragraph__node_field__date($variables);
  }

  // Special case to re-use 'summary' processor for 'content' field.
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  $variables['content'] = $variables['summary'] ?? FALSE;
  unset($variables['summary']);

  $link_text = civictheme_get_field_value($paragraph, 'field_c_p_link_text', TRUE);
  if ($link_text) {
    $variables['links'][] = [
      'text' => $link_text,
      'url' => $node->toUrl()->toString(),
      'is_external' => $node->toUrl()->isExternal(),
      'is_new_window' => $node->toUrl()->isExternal(),
    ];
  }
}
