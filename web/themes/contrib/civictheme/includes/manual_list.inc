<?php

/**
 * @file
 * Manual list paragraph component.
 */

declare(strict_types=1);

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;

/**
 * Implements template_preprocess_paragraph().
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function civictheme_preprocess_paragraph__civictheme_manual_list(array &$variables): void {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  $paragraph = $variables['paragraph'];
  $theme = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $modifier_class = civictheme_extract_modifier_class($variables);

  $card_theme = civictheme_get_field_value($paragraph, 'field_c_p_list_item_theme');
  $with_background = (bool) civictheme_get_field_value($paragraph, 'field_c_p_background');
  $column_count = civictheme_get_field_value($paragraph, 'field_c_p_list_column_count', TRUE, 1);
  $fill_width = civictheme_get_field_value($paragraph, 'field_c_p_list_fill_width', TRUE, FALSE);

  // Set cache tags.
  $variables['#cache']['tags'] = $paragraph->getCacheTags();

  $link_above = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_above_item */
  $link_above_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_above', TRUE);
  if ($link_above_item !== NULL) {
    $link_above = [
      'text' => $link_above_item->get('title')->getString(),
      'url' => $link_above_item->getUrl()->toString(),
      'is_new_window' => $link_above_item->isExternal(),
      'is_external' => $link_above_item->isExternal(),
    ];
  }

  $link_below = NULL;
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_below_item */
  $link_below_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_below', TRUE);
  if ($link_below_item !== NULL) {
    $link_below = [
      'text' => $link_below_item->get('title')->getString(),
      'url' => $link_below_item->getUrl()->toString(),
      'is_new_window' => $link_below_item->isExternal(),
      'is_external' => $link_below_item->isExternal(),
    ];
  }

  // Build row items from referenced paragraphs.
  $rows = [];
  /** @var \Drupal\Core\Entity\ContentEntityInterface[] $items */
  $items = civictheme_get_field_referenced_entities($paragraph, 'field_c_p_list_items', $variables);
  $builder = \Drupal::entityTypeManager()->getViewBuilder('paragraph');
  if ($items) {
    foreach ($items as $item) {
      if (!$item->hasField('field_c_p_reference')) {
        $rows[] = $builder->view($item);
        continue;
      }
      $referenced_item = civictheme_get_field_referenced_entity($item, 'field_c_p_reference', $variables);
      if ($referenced_item instanceof EntityInterface) {
        $rows[] = $builder->view($item);
      }
    }
  }

  // Wrap rows in a grid component.
  $rows_slot = NULL;
  if (!empty($rows)) {
    $rows_slot = [
      '#type' => 'component',
      '#component' => 'civictheme:grid',
      '#props' => array_filter([
        'template_column_count' => (int) $column_count,
        'fill_width' => (bool) $fill_width,
        'items' => $rows,
        'with_background' => $with_background,
        'row_class' => 'row--equal-heights-content row--vertically-spaced',
      ], fn ($v) => $v !== NULL),
    ];
  }

  // Add unique HTML ID via wrapper attributes.
  $attributes = $variables['attributes'] ?? [];
  $attributes['id'] = Html::getUniqueId('ct-manual-list-' . $paragraph->id());

  $variables['content'] = [
    '#type' => 'component',
    '#component' => 'civictheme:list',
    '#props' => array_filter([
      'theme' => $theme,
      'vertical_spacing' => civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing'),
      'with_background' => $with_background,
      'link_above' => $link_above,
      'link_below' => $link_below,
      'modifier_class' => $modifier_class,
    ], fn ($v) => $v !== NULL),
    '#slots' => array_filter([
      'title' => civictheme_get_field_value($paragraph, 'field_c_p_title') ? ['#markup' => civictheme_get_field_value($paragraph, 'field_c_p_title')] : NULL,
      'content' => civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $theme ?? 'light'),
      'rows' => $rows_slot,
    ]),
  ];

  // Preserve attributes for the wrapper.
  $variables['attributes'] = $attributes;
}
