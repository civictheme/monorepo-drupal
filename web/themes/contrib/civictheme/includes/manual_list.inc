<?php

/**
 * @file
 * Automated list paragraph component.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Html;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_manual_list(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  // Remove existing content.
  $variables['content'] = NULL;

  _civictheme_preprocess_paragraph__paragraph_field__title($variables);
  _civictheme_preprocess_paragraph__paragraph_field__content($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link_above($variables);
  _civictheme_preprocess_paragraph__paragraph_field__link_below($variables);
  _civictheme_preprocess_paragraph__paragraph_field__column_count($variables);
  _civictheme_preprocess_paragraph__paragraph_field__fill_width($variables);
  _civictheme_preprocess_paragraph__paragraph_field__theme($variables);
  _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing($variables);
  _civictheme_preprocess_paragraph__paragraph_field__background($variables);

  $variables += [
    'card_theme' => civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme'),
    'cache_tags' => $paragraph->getCacheTags(),
  ];

  /** @var \Drupal\Core\Entity\EntityInterface[] $items */
  $items = civictheme_get_field_referenced_entities($paragraph, 'field_c_p_list_items');
  $builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
  if ($items) {
    foreach ($items as $item) {
      $variables['rows'][] = $builder->view($item);
    }
  }

  $attributes = [];
  $attributes['id'] = Html::getUniqueId('ct-manual-list-' . $paragraph->id());

  $variables['attributes'] = ($variables['attributes'] ?? []) + $attributes;
}
