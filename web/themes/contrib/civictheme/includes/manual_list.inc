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

  // Remove existing content.
  $variables['content'] = NULL;

  // Content values set directly on $variables for use by the list component
  // template. The template uses {% extends 'civictheme:list' %} with a
  // rows_block override to wrap rows in a grid component.
  $variables['theme'] = civictheme_get_field_value($paragraph, 'field_c_p_theme');
  $variables['title'] = civictheme_get_field_value($paragraph, 'field_c_p_title');
  $variables['content'] = civictheme_get_wysiwyg_field_value($paragraph, 'field_c_p_content', TRUE, $variables['theme'] ?? 'light');
  $variables['vertical_spacing'] = civictheme_get_field_value($paragraph, 'field_c_p_vertical_spacing');
  $variables['with_background'] = (bool) civictheme_get_field_value($paragraph, 'field_c_p_background');
  $variables['column_count'] = civictheme_get_field_value($paragraph, 'field_c_p_list_column_count', TRUE, 1);
  $variables['fill_width'] = civictheme_get_field_value($paragraph, 'field_c_p_list_fill_width', TRUE, FALSE);

  // Set cache tags.
  $variables['#cache']['tags'] = $paragraph->getCacheTags();

  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_above_item */
  $link_above_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_above', TRUE);
  if ($link_above_item !== NULL) {
    $variables['link_above'] = [
      'text' => $link_above_item->get('title')->getString(),
      'url' => $link_above_item->getUrl()->toString(),
      'is_new_window' => $link_above_item->isExternal(),
      'is_external' => $link_above_item->isExternal(),
    ];
  }

  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link_below_item */
  $link_below_item = civictheme_get_field_value($paragraph, 'field_c_p_list_link_below', TRUE);
  if ($link_below_item !== NULL) {
    $variables['link_below'] = [
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
  $variables['rows'] = $rows;

  // Add unique HTML ID.
  $attributes = [];
  $attributes['id'] = Html::getUniqueId('ct-manual-list-' . $paragraph->id());

  // Run merging of the attributes last. Do not add any code below this line.
  $variables['attributes'] = ($variables['attributes'] ?? []) + $attributes;
}
