<?php

/**
 * @file
 * Paragraph-related theme helpers.
 *
 * Used as a unified place to populate variables.
 *
 * For readability, similar paragraph and node functions are grouped together
 * and sorted alphabetically.
 */

declare(strict_types=1);

use Drupal\civictheme\CivicthemeConstants;

/**
 * Pre-process for With background paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__background(array &$variables): void {
  $variables['with_background'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_background');
}

/**
 * Pre-process for Column count paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__column_count(array &$variables): void {
  $variables['column_count'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_column_count', TRUE, 1);
}

/**
 * Pre-process for Content paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__content(array &$variables): void {
  $variables['content'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_content');
}

/**
 * Pre-process for Date paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__date(array &$variables): void {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_datetime($date->get('value')->getDateTime(), 'civictheme_short_date');
    $variables['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
  }
}

/**
 * Pre-process for Date node field.
 */
function _civictheme_preprocess_paragraph__node_field__date(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['date'] = civictheme_format_datetime($node->getChangedTime(), 'civictheme_short_date');
    $variables['date_iso'] = civictheme_format_datetime_iso($node->getChangedTime());
  }
}

/**
 * Pre-process for Date range paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__date_range(array &$variables): void {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date_range', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
    $variables['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
    if ($date->get('end_value')->getDateTime()) {
      $variables['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
      $variables['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
    }
  }
}

/**
 * Pre-process for Date range node field.
 */
function _civictheme_preprocess_paragraph__node_field__date_range(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
    if ($date) {
      $variables['date'] = civictheme_format_datetime($date->get('value')->getDateTime());
      $variables['date_iso'] = civictheme_format_datetime_iso($date->get('value')->getDateTime());
      if ($date->get('end_value')->getDateTime()) {
        $variables['date_end'] = civictheme_format_datetime($date->get('end_value')->getDateTime());
        $variables['date_end_iso'] = civictheme_format_datetime_iso($date->get('end_value')->getDateTime());
      }
    }
  }
}

/**
 * Pre-process for Fill width paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__fill_width(array &$variables): void {
  $variables['fill_width'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_fill_width', TRUE, FALSE);
}

/**
 * Pre-process for Image paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__image(array &$variables): void {
  $media = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
}

/**
 * Pre-process for Thumbnail node field.
 */
function _civictheme_preprocess_paragraph__node_field__image(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $media = civictheme_get_field_value($node, 'field_c_n_thumbnail', TRUE);
    if ($media) {
      $variables['image'] = civictheme_media_image_get_variables($media);
    }
  }
}

/**
 * Pre-process for Image paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__image_position(array &$variables): void {
  $variables['image_position'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image_position');
}

/**
 * Pre-process for Link paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__link(array &$variables): void {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_link', TRUE);
  if ($link !== NULL) {
    $variables['link'] = [
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_new_window' => $link->isExternal(),
      'is_external' => $link->isExternal(),
    ];
  }
}

/**
 * Pre-process for Link Above paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__link_above(array &$variables): void {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_link_above', TRUE);
  if ($link !== NULL) {
    $variables['link_above'] = [
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_new_window' => $link->isExternal(),
      'is_external' => $link->isExternal(),
    ];
  }
}

/**
 * Pre-process for Link Below paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__link_below(array &$variables): void {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_link_below', TRUE);
  if ($link !== NULL) {
    $variables['link_below'] = [
      'text' => $link->get('title')->getString(),
      'url' => $link->getUrl()->toString(),
      'is_new_window' => $link->isExternal(),
      'is_external' => $link->isExternal(),
    ];
  }
}

/**
 * Pre-process for Link node field.
 */
function _civictheme_preprocess_paragraph__node_field__link(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['link'] = [
      'text' => $node->getTitle(),
      'url' => $node->toUrl()->toString(),
      'is_new_window' => FALSE,
      'is_external' => FALSE,
    ];
  }
}

/**
 * Pre-process for Links paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__links(array &$variables): void {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem[]|null $links */
  $links = civictheme_get_field_value($variables['paragraph'], 'field_c_p_links');
  if ($links) {
    foreach ($links as $link) {
      $variables['links'][] = [
        'text' => $link->get('title')->getString(),
        'url' => $link->getUrl()->toString(),
        'is_new_window' => $link->isExternal(),
        'is_external' => $link->isExternal(),
      ];
    }
  }
}

/**
 * Pre-process for Summary paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__summary(array &$variables): void {
  $summary = civictheme_get_field_value($variables['paragraph'], 'field_c_p_summary', TRUE);
  if (!empty($summary)) {
    $length = CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH;

    $component_name = _civictheme_get_configurable_component_name_from_bundle($variables['paragraph']->bundle());
    if ($component_name) {
      $length = civictheme_get_theme_config_manager()->loadForComponent($component_name, 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH);
    }

    $variables['summary'] = text_summary($summary, NULL, $length);
  }
}

/**
 * Pre-process for Summary node field.
 */
function _civictheme_preprocess_paragraph__node_field__summary(array &$variables, string $bundle = NULL): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $summary = civictheme_get_field_value($node, 'field_c_n_summary', TRUE);
    if (!empty($summary)) {
      $length = CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH;

      $component_name = _civictheme_get_configurable_component_name_from_bundle($bundle ?? (empty($variables['paragraph']) ? 'none' : $variables['paragraph']->bundle()));
      if ($component_name) {
        $length = civictheme_get_theme_config_manager()->loadForComponent($component_name, 'summary_length', CivicthemeConstants::COMPONENT_SUMMARY_DEFAULT_LENGTH);
      }

      $variables['summary'] = text_summary($summary, NULL, $length);
    }
  }
}

/**
 * Pre-process for Theme paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__theme(array &$variables): void {
  $variables['theme'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_theme');
}

/**
 * Pre-process for Title paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__title(array &$variables): void {
  $variables['title'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_title');
}

/**
 * Pre-process for Title node field.
 */
function _civictheme_preprocess_paragraph__node_field__title(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['title'] = $node->getTitle();
  }
}

/**
 * Pre-process for Topics paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__topics(array &$variables): void {
  $variables['tags'] = civictheme_get_referenced_entity_labels($variables['paragraph'], 'field_c_p_topics');
}

/**
 * Pre-process for Topics node field.
 */
function _civictheme_preprocess_paragraph__node_field__topics(array &$variables): void {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['tags'] = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');
  }
}

/**
 * Pre-process for Vertical spacing paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing(array &$variables): void {
  $variables['vertical_spacing'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_vertical_spacing');
}

/**
 * Pre-process for Location node field.
 */
function _civictheme_preprocess_paragraph__node_field__location(array &$variables): void {
  // Location is a field on the paragraph attached to an Event node through
  // 'field_c_n_location' field. We are only using a first location.
  $node = civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    /** @var \Drupal\paragraphs\Entity\Paragraph[] $locations */
    $locations = civictheme_get_field_value($node, 'field_c_n_location');
    if (!empty($locations)) {
      foreach ($locations as $location) {
        $address = civictheme_get_field_value($location, 'field_c_p_address', TRUE);
        if (!empty($address)) {
          $variables['location'] = $address;

          return;
        }
      }
    }
  }
}
