<?php

/**
 * @file
 * Automated list paragraph component.
 */

use Drupal\Component\Utility\Html;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_manual_list(&$variables) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];

  $attributes = [];
  $attributes['id'] = Html::getUniqueId('listing-' . $paragraph->id());
  $variables['attributes'] = $variables['attributes'] ?? [];
  $variables['modifier_class'] = !empty($variables['attributes']['class']) ? implode(' ', $variables['attributes']['class']) : NULL;
  unset($variables['attributes']['class']);
  $variables['attributes'] = $variables['attributes'] + $attributes;

  $link_above = civictheme_get_field_value($paragraph, 'field_c_p_list_link_above', TRUE);
  $link_above_data = $link_above ? [
    'text' => $link_above->get('title')->getString(),
    'is_external' => $link_above->isExternal(),
    'is_new_window' => $link_above->isExternal(),
    'url' => $link_above->getUrl()->toString(),
  ] : [];

  $link_below = civictheme_get_field_value($paragraph, 'field_c_p_list_link_below', TRUE);
  $link_below_data = $link_below ? [
    'text' => $link_below->get('title')->getString(),
    'is_external' => $link_below->isExternal(),
    'is_new_window' => $link_below->isExternal(),
    'url' => $link_below->getUrl()->toString(),
  ] : [];

  $items = civictheme_get_field_referenced_entities($paragraph, 'field_c_p_list_items');
  $builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
  if ($items) {
    foreach ($items as $item) {
      $variables['rows'][] = $builder->view($item);
    }
  }

  $settings = [
    'theme' => civictheme_get_field_theme_value($paragraph),
    'card_theme' => civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme'),
    'link_above' => $link_above_data,
    'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ?? '',
    'with_background' => civictheme_get_field_value($paragraph, 'field_c_p_background') ?? FALSE,
    'vertical_space' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing') ?? '',
    'column_count' => civictheme_get_field_value($paragraph, 'field_c_p_column_count'),
    'link_below' => $link_below_data,
    'fill_width' => civictheme_get_field_value($paragraph, 'field_c_p_fill_width') ?? FALSE,
    'cache_tags' => $paragraph->getCacheTags(),
  ];

  $variables += $settings;
}
