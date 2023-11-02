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

use Drupal\civictheme\CivicthemeConstants;

/**
 * Pre-process for With background paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__background(&$variables) {
  $variables['with_background'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_background');
}

/**
 * Pre-process for Column count paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__column_count(&$variables) {
  $variables['column_count'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_column_count', TRUE, 1);
}

/**
 * Pre-process for Content paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__content(&$variables) {
  $variables['content'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_content');
}

/**
 * Pre-process for Date paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__date(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $date->get('value')->getDateTime());
    $variables['date_iso'] = date('c', strtotime($date->get('value')->getDateTime()));
  }
}

/**
 * Pre-process for Date node field.
 */
function _civictheme_preprocess_paragraph__node_field__date(&$variables) {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['date'] = civictheme_format_date('civictheme_short_date', $node->getChangedTime());
    $variables['date_iso'] = date('c', strtotime($node->getChangedTime()));
  }
}

/**
 * Pre-process for Date range paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__date_range(&$variables) {
  $date = civictheme_get_field_value($variables['paragraph'], 'field_c_p_date_range', TRUE);
  if ($date) {
    $variables['date'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('value')->getDateTime());
    $variables['date_iso'] = date('c', strtotime($date->get('value')->getDateTime()));
    if ($date->get('end_value')->getDateTime()) {
      $variables['date_end'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('end_value')->getDateTime(), 'j M Y H:i');
      $variables['date_end_iso'] = date('c', strtotime($date->get('end_value')->getDateTime()));
    }
  }
}

/**
 * Pre-process for Date range node field.
 */
function _civictheme_preprocess_paragraph__node_field__date_range(&$variables) {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $date = civictheme_get_field_value($node, 'field_c_n_date_range', TRUE);
    if ($date) {
      $variables['date'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('value')->getDateTime());
      $variables['date_iso'] = date('c', strtotime($date->get('value')->getDateTime()));
      if ($date->get('end_value')->getDateTime()) {
        $variables['date_end'] = civictheme_format_date('civictheme_short_date_and_time', $date->get('end_value')->getDateTime(), 'j M Y H:i');
        $variables['date_end_iso'] = date('c', strtotime($date->get('end_value')->getDateTime()));
      }
    }
  }
}

/**
 * Pre-process for Fill width paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__fill_width(&$variables) {
  $variables['fill_width'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_fill_width', TRUE, FALSE);
}

/**
 * Pre-process for Image paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__image(&$variables) {
  $media = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image', TRUE);
  if ($media) {
    $variables['image'] = civictheme_media_image_get_variables($media);
  }
}

/**
 * Pre-process for Thumbnail node field.
 */
function _civictheme_preprocess_paragraph__node_field__image(&$variables) {
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
function _civictheme_preprocess_paragraph__paragraph_field__image_position(&$variables) {
  $variables['image_position'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_image_position');
}

/**
 * Pre-process for Link paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__link(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_link', TRUE);
  if ($link) {
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
function _civictheme_preprocess_paragraph__paragraph_field__link_above(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_link_above', TRUE);
  if ($link) {
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
function _civictheme_preprocess_paragraph__paragraph_field__link_below(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null $link */
  $link = civictheme_get_field_value($variables['paragraph'], 'field_c_p_list_link_below', TRUE);
  if ($link) {
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
function _civictheme_preprocess_paragraph__node_field__link(&$variables) {
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
function _civictheme_preprocess_paragraph__paragraph_field__links(&$variables) {
  /** @var \Drupal\link\Plugin\Field\FieldType\LinkItem|null[] $link */
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
function _civictheme_preprocess_paragraph__paragraph_field__summary(&$variables) {
  $summary = civictheme_get_field_value($variables['paragraph'], 'field_c_p_summary', TRUE);
  if (!empty($summary)) {
    $length = CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH;

    $card_name = _civictheme_get_card_name_from_bundle($variables['paragraph']->bundle());
    if ($card_name) {
      $length = civictheme_get_theme_config_manager()->loadForComponent($card_name, 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
    }

    $variables['summary'] = text_summary($summary, NULL, $length);
  }
}

/**
 * Pre-process for Summary node field.
 */
function _civictheme_preprocess_paragraph__node_field__summary(&$variables, $bundle = NULL) {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $summary = civictheme_get_field_value($node, 'field_c_n_summary', TRUE);
    if (!empty($summary)) {
      $length = CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH;

      $card_name = _civictheme_get_card_name_from_bundle($bundle ?? (!empty($variables['paragraph']) && $variables['paragraph']->bundle()) ?? 'none');
      if ($card_name) {
        $length = civictheme_get_theme_config_manager()->loadForComponent($card_name, 'summary_length', CivicthemeConstants::CARD_SUMMARY_DEFAULT_LENGTH);
      }

      $variables['summary'] = text_summary($summary, NULL, $length);
    }
  }
}

/**
 * Pre-process for Theme paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__theme(&$variables) {
  $variables['theme'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_theme');
}

/**
 * Pre-process for Title paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__title(&$variables) {
  $variables['title'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_title');
}

/**
 * Pre-process for Title node field.
 */
function _civictheme_preprocess_paragraph__node_field__title(&$variables) {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['title'] = $node->getTitle();
  }
}

/**
 * Pre-process for Topics paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__topics(&$variables) {
  $variables['tags'] = civictheme_get_referenced_entity_labels($variables['paragraph'], 'field_c_p_topics');
}

/**
 * Pre-process for Topics node field.
 */
function _civictheme_preprocess_paragraph__node_field__topics(&$variables) {
  $node = $variables['node'] ?? civictheme_get_field_value($variables['paragraph'], 'field_c_p_reference', TRUE);
  if ($node) {
    $variables['tags'] = civictheme_get_referenced_entity_labels($node, 'field_c_n_topics');
  }
}

/**
 * Pre-process for Vertical spacing paragraph field.
 */
function _civictheme_preprocess_paragraph__paragraph_field__vertical_spacing(&$variables) {
  $variables['vertical_spacing'] = civictheme_get_field_value($variables['paragraph'], 'field_c_p_vertical_spacing');
}

/**
 * Pre-process for Location node field.
 */
function _civictheme_preprocess_paragraph__node_field__location(&$variables) {
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
