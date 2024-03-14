<?php

/**
 * @file
 * Card theme alterations.
 *
 * The per-field processing functions were extracted into helpers in order to
 * preserve data consistency when preprocessing the same fields within different
 * cards.
 * This also avoids "hidden" processing and helps to navigate to the helper
 * within IDE.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__summary($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);

  $variables['subtitle'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_subtitle', TRUE);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_promo_card_ref(array &$variables): void {
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_node().
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function _civictheme_preprocess_node__civictheme_promo_card(array &$variables): void {
  /** @var \Drupal\node\NodeInterface $node */
  $node = $variables['node'];

  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  // @see civictheme_preprocess_views_view_grid()
  $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;

  // Special handling for Event nodes as we are swapping the Promo card with
  // Event card FE component.
  //
  // For nodes rendered in views, it is not possible to switch the view mode
  // based on the result type in the theme layer (the hook does not run).
  // So we are swapping a FE component for Promo card view mode on Event content
  // type to 'Event card' FE component.
  //
  // @see node--civictheme-event--civictheme-promo-card.html.twig
  if ($node->bundle() == 'civictheme_event') {
    _civictheme_preprocess_paragraph__node_field__date_range($variables);
    _civictheme_preprocess_paragraph__node_field__summary($variables, 'civictheme_event_card');
  }
  else {
    _civictheme_preprocess_paragraph__node_field__summary($variables, 'civictheme_promo_card');
    _civictheme_preprocess_paragraph__node_field__date($variables);
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_event_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__summary($variables);
  _civictheme_preprocess_paragraph__paragraph_field__date_range($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);

  $variables['location'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_location', TRUE);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_event_card_ref(array &$variables): void {
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__location($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__node_field__date_range($variables);
  _civictheme_preprocess_paragraph__node_field__topics($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__summary($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);

  $variables['image_as_icon'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_show_image_as_icon') ?? FALSE;
}

/**
 * Implements template_preprocess_node().
 */
function _civictheme_preprocess_node__civictheme_navigation_card(array &$variables): void {
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables, 'civictheme_navigation_card');
  _civictheme_preprocess_paragraph__node_field__link($variables);
  // @see civictheme_preprocess_views_view_grid()
  $variables['theme'] = $variables['node']->component_theme ?? CivicthemeConstants::THEME_LIGHT;
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_navigation_card_ref(array &$variables): void {
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__summary($variables);
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_publication_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__summary($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);

  $document = civictheme_get_field_value($variables['paragraph'], 'field_c_p_document', TRUE) ?? NULL;
  if ($document) {
    $vars = civictheme_media_get_variables($document);
    if ($vars) {
      if (civictheme_get_theme_config_manager()->load('components.publication_card.use_media_name', TRUE)) {
        $vars['name'] = $vars['media_name'];
      }
      $variables['file'] = $vars;
    }
  }
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_subject_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__image($variables);
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_subject_card_ref(array &$variables): void {
  _civictheme_preprocess_paragraph__node_field__image($variables);
  _civictheme_preprocess_paragraph__node_field__title($variables);
  _civictheme_preprocess_paragraph__node_field__link($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}

/**
 * Implements template_preprocess_paragraph().
 */
function civictheme_preprocess_paragraph__civictheme_service_card(array &$variables): void {
  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__links($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
}
